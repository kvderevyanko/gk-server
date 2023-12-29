let rangeList = {
    'bright':{'label':'Яркость', 'min' : 0, 'max' : 225, 'step' : 1, 'value' : 30},
    'delay':{'label':'Задержка', 'min' : 10, 'max' : 225, 'step' : 1, 'value' : 30},
    'mode_options':{'label':'Опции режима', 'min' : 1, 'max' : 15, 'step' : 1, 'value' : 1}
};
let modeList = {'off': 'Выключить', 'static': 'Статичный', 'static-soft-blink': 'Статичный мягкий мигающий',
    'static-soft-random-blink': 'Случайный мягкий мигающий', 'round-static': 'По кругу статичный', 'round-random': 'По кругу случайный', 'rainbow': 'Радуга',
    'rainbow-circle': 'Радуга по кругу',};

gi('rangeList').innerHTML = generateRange(rangeList);

let selectOptions = "";
Object.keys(modeList).forEach(function (key) {
    selectOptions += '<option value="' + key + '">' + modeList[key] + '</option>';
});
gi('modeList').innerHTML = selectOptions;
setTimeout(loadSettings, 100);

