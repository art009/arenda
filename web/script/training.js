function startShowTraining(th,e){
    e.preventDefault();
    e.stopPropagation();
    var src = $(th).attr('data-scr'),
        modal_youtube = $('#youtube_modal'),
        modal_title = $(th).attr('data-title');
    modal_youtube.find('iframe').attr('src', src);
    modal_youtube.find('.modal-header').prepend(modal_title);
    modal_youtube.modal('show');
}