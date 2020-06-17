
var img = document.getElementsByTagName("img");

for(var i= 0;i< img.length;i++){
var curenimg = img[i]
console.log(curenimg.getAttribute('src'));
curenimg.addEventListener("click", function() {
    window.open(curenimg.getAttribute('src'));
});
}

