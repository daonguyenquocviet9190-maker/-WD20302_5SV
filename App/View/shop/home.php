<!-- BANNER SLIDER -->
<div class="slider">
    <img src="App/img/banner0.jpg" class="slide active">
    <img src="App/img/banner1.png" class="slide">
    <img src="App/img/banner2.png" class="slide">
</div>
<script>
let index = 0;
const slides = document.querySelectorAll(".slide");

setInterval(() => {
    slides[index].classList.remove("active");
    index = (index + 1) % slides.length;
    slides[index].classList.add("active");
}, 3000);
</script>