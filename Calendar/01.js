const a = 1;
let b = CallMe(3);

function CallMe(a){
    console.log(a)
    return 2;
}

let c = () => {
    console.log(9)
    return 9
}

window.onload = () => {
    console.log(6)
}

console.log(b)