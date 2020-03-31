const conversation = {
    questions: [
        {
            title: "Hi hallo,",
            text: "Je hebt aangegeven dat je hulp wil met het vinden van een passende vacature. Klopt dat?",
            name: "confirmation",
            options:
                [
                    {
                        text: "Dat klopt",
                        type: "buttonPrimary",
                        action: "next",
                    },
                    {
                        text: "Toch niet",
                        type: "buttonPrimary",
                        action: "previous",
                    }
                ]
        },
        {
            title: "Super!",
            text: "We helpen je graag. Met wie hebben wij het genoegen?",
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
            title: "Hi [Naam],",
            text: "Naar wat voor vacature ben je op zoek? Je kunt ook meerdere opties aanklikken.",
            respond_type: "text",
            name: "jobs",
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
        },
        {
            title: "Top, staat genoteerd.",
            text: "En met welke technieken kan/wil je werken en/of welke skills heb je?",
            respond_type: "text",
            name: "skills",
            options:
                [
                    {
                        type: "autocomplete",
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
            title: "Daar kunnen we wel wat mee!",
            text: "Alleen nog je e-mailadres, zodat we je kunnen bereiken voor passende vacature, en dan zijn we good to go",
            respond_type: "text",
            name: "email",
            options:
                [
                    {
                        text: "Emailadres",
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
            title: "Done!",
            text: "Zodra we een vacature hebben die aansluit bij jouw wensen, sturen we je een mailtje.",
            respond_type: "text",
            name: "email",
            options:
                [
                    {
                        text: "Over en sluiten",
                        type: "buttonPrimary",
                        action: "next",
                    }
                ]
        },
    ],
    values: {
        confirmation: "Dat klopt!",
        name: "",
        jobs: [],
        skills: [],
        email: "",
    }
};

let curStep = 0;
let skillsList = [];

function initHelper(step, skills) {
    let {title, text, name} = conversation.questions[step];
    let questionComponent = '<div class="entry"><div class="question"><h2>' + title + '</h2><p>' + text + '</p></div></div>';

    skills.forEach(function (skill) {
        skillsList.push(skill.name);
    });

    jQuery('#helperForm .chat').append(questionComponent);
    jQuery('#helperForm .response-field').html(generateOptions());
}

function updateHelper(type) {
    let {title, text} = conversation.questions[curStep];
    let value;

    if(curStep === conversation.questions.length-1) {
        const form_data = new FormData();
        form_data.append('action', 'addLead');

        Object.keys(conversation.values).forEach(function(key){
            form_data.append(key, conversation.values[key]);
        });

        jQuery.ajax({
            type: 'POST',
            url: adminURLs.ajax,
            processData: false,
            contentType: false,
            data: form_data,
            success: function(response){
                console.log(response);
            },
        });
    }

    if(curStep != 0) {
        value = conversation.values[conversation.questions[curStep-1].name];
    } else {
        value = conversation.values[conversation.questions[0].name];
    }

    console.log(title + " " + title.includes('[Naam]'));

    if(title.includes('[Naam]')) {
        title = title.replace("[Naam]", conversation.values['name']);
    }

    let questionComponent = '<div class="entry"><div class="question"><h2>' + title + '</h2><p>' + text + '</p></div></div>';
    let answerValue = Array.isArray(value) ? value.toString() : value;
    let answerComponent = '<div class="entry"><div class="answer"><p>' + answerValue + '</p></div></div>';

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

    const chat = document.getElementsByClassName('chat')[0];
    chat.scrollTop = chat.scrollHeight - chat.clientHeight;
    if(curStep === 3) { renderSkills() };
}

function generateOptions() {
    let component = '';
    let options = '';
    let inputs = '';

    for(let i = 0; i < conversation.questions[curStep].options.length; i++) {
        let {text, type, action} = conversation.questions[curStep].options[i];
        let name = conversation.questions[curStep].name;

        let next = action === "next" ? true : action === "previous" ? false : null;

        switch(type) {
            case "buttonPrimary":
                options += '<a onclick="changeStep('+next+')" class="button primary dark">' + text + '</a>';
                break;
            case "buttonSecondary":
                options += '<a onclick="changeStep('+next+')" class="button secondary dark">' + text + '</a>';
                break;
            case "textInput":
                inputs += '<input type="text" id="valueInput" name="'+ name +'" value="'+ conversation.values[name] +'" placeholder="'+ text +'" />';
                break;
            case "jobSelector":
                inputs += jobSelector();
                break;
            case "autocomplete":
                inputs += autocompleteInput();
                break;
        }
    }

    component += inputs;
    component += '<div class="buttons">' + options + '</div>';

    return component;
}

function setValues() {
    let inputs = jQuery('#helperForm input#valueInput');
    let allowNext = false;

    if(inputs.length !== 0) {
        conversation.values[inputs[0].name] = Array.isArray(conversation.values[inputs[0].name]) ? [] : "";

        jQuery(inputs).each(function(i) {
            let value = jQuery(this).val();
            let name = jQuery(this).attr("name");

            if(inputs[i].type === "checkbox") {
                if(inputs[i].checked) {
                    if(inputs[i].value !== ""){
                        conversation.values[name].push(value);
                    }
                }
            } else {
                if (jQuery.trim(value) === "") {
                    conversation.values[name] = "";
                    return false;
                }

                conversation.values[name] += value;
            }

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
    if(next) {
        if(curStep < conversation.questions.length-1) {
            if (setValues()) {
                curStep++;
                updateHelper("next");
            }
        } else if(curStep === conversation.questions.length-1) {

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
    let jobTitles = helperJobs;

    let jobComponent = '<div class="categoryTabs">';
    let buttonGroupComponent = '';
    let inputGroupComponent = '';

    for (let [category, jobs] of Object.entries(jobTitles)) {
        let displayTab = category === 'agile' ? 'display: flex;' : 'display: none;';
        let activeTab = category === 'agile' ? 'active' : '';

        let tabComponent = '<button onclick="switchTab(\'' + category + '\')" id="' + category + '" class="tabmenu ' + activeTab + '">' + category + '</button>';
        buttonGroupComponent += tabComponent;

        let inputContainerComponent = '';

        for (let i = 0, len = jobs.length; i < len; i++) {
            let checked = conversation.values['jobs'].includes(jobs[i]) ? 'checked' : '';
            let inputComponent = '<label><input type="checkbox" id="valueInput" name="jobs" value="' + jobs[i] + '"'+ checked + ' />' + jobs[i] + '</label>';

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
    document.getElementById('content_' + category).style.display = 'flex';

    let tabs = document.getElementsByClassName("tabmenu");
    for (let i = 0; i < tabs.length; i++) {
        tabs[i].className = 'tabmenu';
    }
    document.getElementById(category).className = 'tabmenu active';
}

function autocompleteInput() {
    let component = '';

    component += '<div class="tags" style="margin-bottom: 8px;"></div>';
    component += '<div class="autocomplete">';
    component += '<input id="skillInput" type="text" name="skills" placeholder="Skill" onkeyup="autocompleteSkills(skillsList)">';
    component += '</div>';

    return component;
}

function autocompleteSkills(arr) {
    const inp = document.getElementById("skillInput");
    const val = inp.value;

    removeItems();
    if(!val || conversation.values["skills"].length >= 6 ) { return false; }

    const a = document.createElement("div");
    a.setAttribute("id", "autocomplete-list");
    a.setAttribute("class", "autocomplete-items");

    inp.parentNode.appendChild(a);

    for (let i = 0; i < arr.length; i++) {
        if (arr[i].substr(0, val.length).toUpperCase() == val.toUpperCase()) {

            const skill = arr[i].replace(/^\w/, c => c.toUpperCase());

            const b = document.createElement("div");
            b.innerHTML = "<strong>" + skill.substr(0, val.length) + "</strong>";
            b.innerHTML += skill.substr(val.length);
            b.innerHTML += "<input type='hidden' value='" + skill + "'>";
            b.addEventListener("click", function(e) {
                const skill = this.getElementsByTagName("input")[0].value;
                inp.value = "";
                conversation.values['skills'].push(skill);


                const newSkills = skillsList.filter(e => e !== arr[i]);
                skillsList = newSkills;

                renderSkills();
                removeItems();
            });
            a.appendChild(b);
        }
    }

    function removeItems() {
        const x = document.getElementsByClassName("autocomplete-items");
        for (let i = 0; i < x.length; i++) {
            x[i].parentNode.removeChild(x[i]);
        }
    }
}

function renderSkills() {
    const container = document.getElementsByClassName('tags')[0];
    const skills = conversation.values['skills'];

    container.innerHTML = "";

    skills.forEach(function (skill) {
        const c = document.createElement("div");
        c.setAttribute("class", "tag");
        c.innerHTML = "<img class='icon' src='"+ wpDirectory.templateUrl +"/assets/images/icons/cross.svg'> " + skill;
        c.addEventListener("click", function(e) {
            removeItem(skill);
        });
        container.appendChild(c);
    });
}

function removeItem(item) {
    const newSkills = conversation.values['skills'].filter(e => e !== item);
    conversation.values['skills'] = newSkills;

    skillsList.push(item);

    renderSkills();
}