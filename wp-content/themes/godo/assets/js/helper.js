const conversation = {
    questions: [
        {
            title: "Hallo!",
            text: "Je hebt aangegeven dat je hulpt nodig hebt met het vinden van een passende vacature. Klopt dit?",
            name: "confirmation",
            options:
                [
                    {
                        text: "Dat klopt!",
                        type: "buttonPrimary",
                        action: "next",
                    },
                    {
                        text: "Ik heb toch geen hulp nodig",
                        type: "buttonPrimary",
                        action: "previous",
                    }
                ]
        },
        {
            title: "Awesome!",
            text: "Wij helpen je graag. Met wie hebben wij het genoegen?",
            respond_type: "text",
            name: "name",
            options:
                [
                    {
                        text: "John Doe",
                        type: "textInput",
                        action: "value"
                    },
                    {
                        text: "Vorige stap",
                        type: "buttonSecondary",
                        action: "previous",
                    },
                    {
                        text: "Ok, next",
                        type: "buttonPrimary",
                        action: "next",
                    }
                ]
        },
        {
            title: "Hey!",
            text: "Naar wat voor functie(s) ben je opzoek? Je kunt ook meerdere functies aangeven!",
            respond_type: "text",
            options:
                [
                    {
                        type: "jobSelector",
                        action: "value",
                    },
                    {
                        text: "Vorige stap",
                        type: "buttonSecondary",
                        action: "previous",
                    },
                    {
                        text: "Ok, next",
                        type: "buttonPrimary",
                        action: "next",
                    }
                ]
        }
    ],
    values: {
        confirmation: "Dat klopt!",
        name: "",
        jobs: "",
        skills: "",
        email: "",
    }
};

let curStep = 0;

function initHelper(step) {
    let {title, text, name} = conversation.questions[step];
    let questionComponent = '<div class="question"><h2>' + title + '</h2><p>' + text + '</p></div>';

    jQuery('#helperForm .chat').append(questionComponent);
    jQuery('#helperForm .response-field').html(generateOptions());
}

function updateHelper(type) {
    let {title, text} = conversation.questions[curStep];
    let value;

    if(curStep != 0) {
        value = conversation.values[conversation.questions[curStep-1].name];
    } else {
        value = conversation.values[conversation.questions[0].name];
    }

    let questionComponent = '<div class="question"><h2>' + title + '</h2><p>' + text + '</p></div>';
    let answerComponent = '<div class="answer"><p>' + value + '</p></div>';

    switch(type) {
        case "next":
            jQuery(answerComponent).appendTo(jQuery('#helperForm .chat'));
            jQuery(questionComponent).appendTo(jQuery('#helperForm .chat'));
            break;

        case "previous":
            jQuery('#helperForm .chat .question').last().remove();
            jQuery('#helperForm .chat .answer').last().remove();
            break;
    }

    jQuery('#helperForm .response-field').html(generateOptions());
}

function generateOptions() {
    let component = '';

    for(let i = 0; i < conversation.questions[curStep].options.length; i++) {
        let {text, type, action} = conversation.questions[curStep].options[i];
        let name = conversation.questions[curStep].name;

        let next = action === "next" ? true : action === "previous" ? false : null;

        switch(type) {
            case "buttonPrimary":
                component += '<a onclick="changeStep('+next+')" class="button primary dark">' + text + '</a>';
                break;
            case "buttonSecondary":
                component += '<a onclick="changeStep('+next+')" class="button secondary dark">' + text + '</a>';
                break;
            case "textInput":
                component += '<input type="text" name="'+ name +'" value="'+ conversation.values[name] +'" placeholder="'+ text +'" />';
                break;
            case "jobSelector":
                component += jobSelector();
                break;
        }
    }

    return component;
}

function setValues() {
    let inputs = jQuery('#helperForm input');
    let allowNext = false;

    if(inputs.length !== 0) {
        jQuery(inputs).each(function(i) {
            let value = jQuery(this).val();
            let name = jQuery(this).attr("name");

            if(jQuery.trim(value) === "") {
                conversation.values[name] = "";
                return false;
            }

            conversation.values[name] = value;

            if(i === inputs.length-1) {
                allowNext = true;
            }
        });
    } else {
        allowNext = true;
    }

    return allowNext;
}

function changeStep(next) {
    if(next && curStep < conversation.questions.length-1) {
        if(setValues()) {
            curStep++;
            updateHelper("next");
        }
    } else if(!next) {
        if(curStep > 0) {
            curStep--;
            updateHelper("previous")
        } else {
            curStep = 0;
            jQuery('#helper .close').trigger('click');
        }
    };
}

function jobSelector() {
    let jobTitles = {
        agile: ["Scrum master", "Product Owner", "Agile Coach"],
        design: ["UX Designer", "UI Designer", "Product Designer", "Visual Designer"]
    };

    let jobComponent = '<div class="categoryTabs">';
    let buttonGroupComponent = '';
    let inputGroupComponent = '';

    for (let [category, jobs] of Object.entries(jobTitles)) {
        let displayTab = category === 'agile' ? 'display: block;' : 'display: none;';
        let activeTab = category === 'agile' ? 'active' : '';

        let tabComponent = '<button onclick="switchTab(\'' + category + '\')" id="' + category + '" class="tabmenu ' + activeTab + '">' + category + '</button>';
        buttonGroupComponent += tabComponent;

        let inputContainerComponent = '';

        for (let i = 0, len = jobs.length; i < len; i++) {
            let inputComponent = '<label><input type="checkbox" name="jobs[]" value="' + jobs[i] + '" />' + jobs[i] + '</label>';

            inputContainerComponent += inputComponent;
        }

        inputGroupComponent = '<div id="content_' + category + '" class="tabcontent" style="' + displayTab + '">' + inputContainerComponent + '</div>';
        jobComponent += inputGroupComponent;
    }

    jobComponent += '<div class="tabGroup">' + buttonGroupComponent + '</div>';

    return jobComponent;
}

function switchTab(category) {
    let tabContent = document.getElementsByClassName("tabcontent");
    for (let i = 0; i < tabContent.length; i++) {
        tabContent[i].style.display = 'none'; // hide all tab content
    }
    document.getElementById('content_' + category).style.display = 'block';

    let tabs = document.getElementsByClassName("tabmenu");
    for (let i = 0; i < tabs.length; i++) {
        tabs[i].className = 'tabmenu';
    }
    document.getElementById(category).className = 'tabmenu active';
}