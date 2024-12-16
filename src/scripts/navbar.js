// Este script sirve para controlar el indicador del navbar de la aplicación, haciendo que el usuario sepa en que apartado se encuentra 
// y sea mas que evidente visualmente (todo gracias a el evento mouseover y 2 clases)
let listItems2 = document.querySelectorAll('.navbar2 li');
let pointItem2 = document.querySelector('.navbar2 .point');

function setActive2() {
    listItems2.forEach(item => item.classList.remove('active'));
    pointItem2.classList.add('active');
}

function activeLink2(event) {
    event.preventDefault();
    listItems2.forEach(item => item.classList.remove('active'));
    this.classList.add('active');
}

listItems2.forEach(item => item.addEventListener('mouseover', activeLink2));

//Si no hay interacción con el resto de apartados que hay en el navbar, gracias a la siguiente funcion despues del evento mouseout (que 
//es como decir que no se hace hover), el indicador vuelve al apartado en el que el usuario se encuentra en ese momento
document.querySelector('.navbar2').addEventListener('mouseout', function(event) {
    if (!event.relatedTarget || !event.relatedTarget.closest('.navbar2')) {
        setActive2();
    }
});
