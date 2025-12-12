let container = document.getElementById('container');

toggle = () => {
    container.classList.toggle('sign-in');
    container.classList.toggle('sign-up');
};

setTimeout(() => {
    container.classList.add('sign-in');
}, 200);

function passer() {
    location.href = "./pages/home.php";
}

let imageUrl = document.getElementById("imageUrl");
let previewImg = document.getElementById("previewImg");

imageUrl.addEventListener("input", () => {
    previewImg.src = imageUrl.value;

    previewImg.onerror =()=>{
        previewImg.src = "./assets/userProfil.webp"
    }
});


