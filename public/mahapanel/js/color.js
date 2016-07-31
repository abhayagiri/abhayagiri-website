function toRGB( h ) {
    var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(h);
    return [parseInt(result[1], 16),parseInt(result[2], 16),parseInt(result[3], 16)]
}   

function toHex(num){
    return pad(parseInt(num,16),6);
}

function pad(number, length) {
    var str = '' + number;
    while (str.length < length) {
        str = '0' + str;
    }
    return str;
}

function step(elm,attr,colors){
    if(i < colors.length){    
        next = true;
        arr1 = toRGB(colors[i]);
        rgb = $(elm).css(attr);
        arr2 = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
        for(x=1;x<4;x++){
            arr1[x-1] = parseInt(arr1[x-1]);
            arr2[x] = parseInt(arr2[x]);
            if(arr1[x-1] != arr2[x]){
                arr2[x] = arr2[x] > arr1[x-1] ? arr2[x]-1 : arr2[x]+1;
                next = false;
            }
        }
        $(elm).css(attr, 'rgb('+arr2[1]+','+arr2[2]+','+arr2[3]+')');
        if(next)i++;
        return false;
    }
    i=0;
}

function toColor(elm,attr,colors){
    this.i = 0;
    if(colors.length>1){
        $(elm).css(attr,colors[0]);
    }
    setInterval(function () {
        step(elm,attr,colors);
    },100);
}
