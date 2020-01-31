for(let i = 0; i < conversation.questions.length; i++) {
    let {title, text, name} = conversation.questions[i];
    let value = conversation.values[name];

    let questionComponent = '<div class="question"><h2>' + title + '</h2><p>' + text + '</p></div>';

    let historyQuestionComponent = '<div class="question"><h4>' + title + '</h4><p>' + text + '</p></div>';
    let historyAnswerComponent = '<div class="answer"><p>' + value + '</p></div>';

    if(i === curStep) {
        jQuery('#helperForm .current').html(questionComponent);
        jQuery('#helperForm .response-field').html(generateOptions());
    } else if(i <= curStep) {
        jQuery('#helperForm .history').append(historyQuestionComponent);
        jQuery('#helperForm .history').append(historyAnswerComponent);
    }
}