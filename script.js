let navbar = document.querySelector('.header .flex .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
}

let account = document.querySelector('.user-account');

document.querySelector('#user-btn').onclick = () =>{
   account.classList.add('active');
}

document.querySelector('#close-account').onclick = () =>{
   account.classList.remove('active');
}

let myOrders = document.querySelector('.my-orders');

document.querySelector('#order-btn').onclick = () =>{
   myOrders.classList.add('active');
}

document.querySelector('#close-orders').onclick = () =>{
   myOrders.classList.remove('active');
}

let cart = document.querySelector('.shopping-cart');

document.querySelector('#cart-btn').onclick = () =>{
   cart.classList.add('active');
}

document.querySelector('#close-cart').onclick = () =>{
   cart.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   myOrders.classList.remove('active');
   cart.classList.remove('active');
};

let slides = document.querySelectorAll('.home-bg .home .slide-container .slide');
let index = 0;

function next(){
   slides[index].classList.remove('active');
   index = (index + 1) % slides.length;
   slides[index].classList.add('active');
}

function prev(){
   slides[index].classList.remove('active');
   index = (index - 1 + slides.length) % slides.length;
   slides[index].classList.add('active');
}

document.addEventListener('DOMContentLoaded', function () {
    const accordions = document.querySelectorAll('.faq .accordion');

    accordions.forEach((accordion) => {
        const heading = accordion.querySelector('.accordion-heading');
        const content = accordion.querySelector('.accordion-content');

        heading.addEventListener('click', () => {
            accordions.forEach((otherAccordion) => {
                if (otherAccordion !== accordion) {
                    otherAccordion.classList.remove('active');
                    otherAccordion.querySelector('.accordion-content').style.display = 'none';
                }
            });

            accordion.classList.toggle('active');
            content.style.display = content.style.display === 'block' ? 'none' : 'block';
        });
    });
});
        
    
