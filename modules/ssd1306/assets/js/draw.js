let heightPx = $("#heightPx");
let widthPx = $("#widthPx");
let sizeTd = $("#sizeTd");
let tableDraw = $("#tableDraw");
let btnColorBlock = $("#btnColorBlock");
let saveDraw = $("#saveDraw");
let imagesList = $("#imagesList");
let images = {};

btnColorBlock.on('click', ".btnDraw", function () {
    if($(this).hasClass("btnDrawActive")) {
        $(this).removeClass("btnDrawActive");
    } else {
        btnColorBlock.find(".btnDrawActive").removeClass("btnDrawActive");
        $(this).addClass("btnDrawActive");
    }
});

tableDraw.on('click', '.drawTd', function (e) {
    if(e.buttons === 1) { //Зажата левая кнопка при наведении
        setTdBgColor($(this));
    }
});

saveDraw.on('click', function () {
    saveDrawToServer();
})

generateList(heightPx, 2, 64, 2);
generateList(widthPx, 8, 128, 8);

heightPx.val(16);
widthPx.val(16);


sizeTd.on('change', function(){changeSize();});
heightPx.on('change', function(){createTable();});
widthPx.on('change', function(){createTable();});

createTable();
getImagesList();

//Генерация списка изображений на основе данных из images
function generateImagesList(){
    cl("generateImagesList");
    imagesList.html('');
    //cl(images);
    //Получаем изображение из кода
    $.each(images, function (id, image) {
        image['htmlCode'] = codeToImage(image['code']);
        imagesList.append('<div class="prevTd">'+image['htmlCode']+'</div>')
    });
    cl(images);
}

//Конвертируем код в изображение для показа пользователю
function codeToImage(code, size=2){
    let htmlCode;
    if(code && Object.size(code)) {
        let height = Object.size(code);
        if(code[0] && Object.size(code[0])) {
            let width = Object.size(code[0]);
            htmlCode = "<table>";
            for (let h = 0; h < height; h++) {
                htmlCode +="<tr>";
                for (let w = 0; w < width; w++) {
                    htmlCode +='<td style="width: '+size+'px; height: '+size+'px; background-color: '+(Number(code[h][w])?'#000':'#fff')+';"></td>';
                }
                htmlCode +="</tr>";
            }
            htmlCode +="</table>";
        }
    }
    return htmlCode;
}

//Длина объекта
Object.size = function(obj) {
    var size = 0,
        key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};


//Получение списка изображений
function getImagesList(){
    $.get(urlGetImagesList, function (data) {
        images = {};
        $.each(data, function (id, image) {
            if(image['code']) {
                try {
                    image['code'] = JSON.parse(image['code']);
                } catch (err) {cl(err)}
            }
            images[id] = image;
        });
        generateImagesList();
    })
}

//Сохранение на сервере пиксельного изображения
function saveDrawToServer(){
    let params = generateImageParams();
    cl(params)
    $.post(urlSaveImage, {
        _csrf:$("[name='csrf-token']").attr('content'),
        params:params
    }, function (data) {
        cl(data);
        getImagesList();
    })
}

//Получение основных параметров изображения
function generateImageParams(){
    let height = heightPx.val();
    let width = widthPx.val();
    let size = sizeTd.val();
    let code = {};
    $.each(tableDraw.find('tr'), function (i, rows) {
        code[i] = [];
        $.each($(rows).find('td'), function () {
            code[i].push((Number($(this).attr("val-int"))?1:0));
        })
    });
    return {
        height:height,
        width:width,
        size:size,
        code:JSON.stringify(code),
    };
}

//Установка цвета и свойств пикселя для изображение
function setTdBgColor(tdBlock){
    let btn = btnColorBlock.find(".btnDrawActive");
    if(btn.length && btn.data('color')) {
        tdBlock.css("background-color", btn.data('color'));
        tdBlock.attr("val-int", btn.data('int'));
    }
}

//Создание страницы для пиксельного изображения
function createTable(){
    let height = heightPx.val();
    let width = widthPx.val();
    let size = sizeTd.val();

    let htmlCode = "<table>";

    for (let h = 0; h < height; h++) {
        htmlCode +="<tr>";
        for (let w = 0; w < width; w++) {
            htmlCode +="<td class='drawTd'></td>";
        }
        htmlCode +="</tr>";
    }
    htmlCode +="</table>";

    tableDraw.html(htmlCode);
    let drawTd = $(".drawTd");
    drawTd.css({'width':size, 'height':size});
    drawTd.hover(function (e) {
        if(e.buttons === 1) { //Зажата левая кнопка при наведении
            setTdBgColor($(this));
        }
    });

    drawTd.mousedown(function (e) {
        setTdBgColor($(this));
    });
}

//Изменение размеров ячейки пиксельного изображения
function changeSize (){
    let size = sizeTd.val();
    $(".drawTd").css({'width':size, 'height':size});
}

//Генерация цифровых выпадающих списков
function generateList(div, min, max, step){
    for(let i=min; i<=max; step) {
        div.append('<option value="'+i+'">'+i+'</option>')
        i+=step;
    }
}