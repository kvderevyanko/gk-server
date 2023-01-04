
let sliderWs = $(".sliderWs");
let saveAnimation = $(".saveAnimation");
let blockWsRequest;
let waitWsRequest;

sliderWs.on('input change', function() {
    let sliderBlock = $(this).parent('.slider_block');
    sliderBlock.find('.badge').text($(this).val());
})

sliderWs.on('change', function() {
    commandWs($(this).data('device'))
})


$('[name="mode"]').on('change', function() {
    commandWs($(this).data('device'))
})

$('[name="singleColor"]').on('change', function() {
    commandWs($(this).data('device'))
})

$('[name="gradientColor"]').on('change', function() {
    commandWs($(this).data('device'))
})

$('[name="modeOptions"]').on('change', function() {
    commandWs($(this).data('device'))
})

let deviceRepeatWs = 0;

function commandWs(deviceId) {
    if(blockWsRequest) {
        waitWsRequest = deviceId;
        return false;
    }

    if(deviceRepeatWs === 0)
        deviceRepeatWs = 1;

    blockWsRequest = true;
    waitWsRequest = false;
    let block = $("#ws_block_"+deviceId);
    let params = {deviceId:deviceId};
    $.each(block.find('input'), function() {
        params[$(this).attr('name')] = $(this).val();
    });
    $.each(block.find('select'), function() {
        params[$(this).attr('name')] = $(this).val();
    })
    openWaitRequest(deviceId, deviceRepeatWs);

    $.get(urlCommandWs, params, function(data) {
        blockWsRequest = false;
        if(waitWsRequest)
            commandWs(waitWsRequest)
        hideWaitRequest(deviceId, deviceRepeatWs);
        deviceRepeatWs = 0;
    }).fail(function() {
        blockWsRequest = false;
        waitWsRequest = false;
        if(deviceRepeatWs < 5) {
            deviceRepeatWs++;
            commandWs(deviceId);
        } else {
            hideWaitRequest(deviceId, deviceRepeatWs)
            alert( "Ошибка отправки запроса после 5 попыток" );
            deviceRepeatWs = 0;
        }

    })
}

saveAnimation.on('click', function (){
    /*let block = $("#ws_block_"+$(this).data('device'));
    let params = {deviceId:$(this).data('device')};
    $.each(block.find('input'), function() {
        params[$(this).attr('name')] = $(this).val();
    });
    $.each(block.find('select'), function() {
        params[$(this).attr('name')] = $(this).val();
    });*/



})