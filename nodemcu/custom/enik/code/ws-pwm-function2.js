function generateRange(rangeList){
    let rangeListHtml = "";
    Object.keys(rangeList).forEach(function (key) {
    rangeListHtml += '<label><div class="label-name">' + rangeList[key]['label'] + '</div><input name="' + key + '" onChange="setVal(this.value, \'' + key + 'BId\')" ' +
        'type="range" min="' + rangeList[key]['min'] + '" max="' + rangeList[key]['max'] + '" step="' + rangeList[key]['step'] + '" ' +
        'value="' + rangeList[key]['value'] + '"  class="range-slider" id="' + key + '"> <span class="badge" id="' + key + 'BId">' + rangeList[key]['value'] + '</span></label>';
    });
return rangeListHtml;
}

function loadSettings(){
    let xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (xhr.readyState == XMLHttpRequest.DONE) {
            try {
                let response = JSON.parse(xhr.response);
                cl(response)
                Object.keys(response).forEach(function (key) {
                    if (key === "mode") {
                        gi('modeList').value = response[key];
                    } else if (key === "single_color") {
                        let colorList = response[key];
                        colorList = colorList.replace("[", '')
                        colorList = colorList.replace("]", '')
                        if(colorList) {
                            colorList = colorList.split(",");
                            if(colorList[0] && colorList[1] && colorList[2]) {
                                gi(key).value = grbToHex(Number(colorList[0]), Number(colorList[1]), Number(colorList[2]));
                            }
                        }
                    }else {
                        if(gi(key)) {
                            gi(key).value = response[key];
                        }
                        if(gi(key+'BId')){
                            gi(key+'BId').innerHTML = response[key];
                        }
                    }
                });
            } catch (err) {
                cl(err)
            }
        }
    }
    xhr.open('GET', '/json/ws-action2.json', true);
    xhr.send(null);
}
