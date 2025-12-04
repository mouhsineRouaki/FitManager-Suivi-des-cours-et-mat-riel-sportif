let container = document.getElementById('container')

toggle = () => {
	container.classList.toggle('sign-in')
	container.classList.toggle('sign-up')
}

setTimeout(() => {
	container.classList.add('sign-in')
}, 200)
function passer(){
    location.href = "./pages/home.php"
}
function passerAuPageCours(){
    location.href = "./cours.php"
}



