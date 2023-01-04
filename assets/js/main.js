function cl(str) {
    console.log(str);
}

let waitRequest = $("#waitRequest");
let waitRequestId = $("#waitRequestId");
let requestRepeat = $("#requestRepeat");
function openWaitRequest(id, repeat, message) {
    //wait_request.show();
    if(repeat) {
        requestRepeat.show();
        requestRepeat.find('span').text(repeat);
    } else {
        requestRepeat.hide();
    }
    waitRequestId.text(id);
    waitRequest.slideDown();
}

function hideWaitRequest(id, repeat, message) {
    if(repeat) {
        requestRepeat.show();
        requestRepeat.find('span').text(repeat);
    } else {
        requestRepeat.hide();
    }
    waitRequestId.text(id);
    waitRequest.slideUp();
}

function updateSortable(div){
    div.sortable();
    div.disableSelection();
}
