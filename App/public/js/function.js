let index = 0;
const slides = document.querySelectorAll(".slide");

setInterval(() => {
    slides[index].classList.remove("active");
    index = (index + 1) % slides.length;
    slides[index].classList.add("active");
}, 3000);

const list = document.getElementById("cateList");
const dotsContainer = document.getElementById("dotsContainer");
const cards = list.querySelectorAll(".category-card");

const cardsPerPage = 5;
const totalPages = Math.ceil(cards.length / cardsPerPage);

// Tạo dots
for (let i = 0; i < totalPages; i++) {
    const dot = document.createElement("span");
    dot.classList.add("dot");
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => {
        list.scrollTo({ left: i * list.offsetWidth, behavior: "smooth" });
        setActiveDot(i);
    });
    dotsContainer.appendChild(dot);
}

function setActiveDot(index) {
    const allDots = dotsContainer.querySelectorAll(".dot");
    allDots.forEach(dot => dot.classList.remove("active"));
    allDots[index].classList.add("active");
}

// Scroll bằng nút
let currentPage = 0;

function scrollToRight() {
    if (currentPage < totalPages - 1) currentPage++;
    list.scrollTo({ left: currentPage * list.offsetWidth, behavior: "smooth" });
    setActiveDot(currentPage);
}

function scrollToLeft() {
    if (currentPage > 0) currentPage--;
    list.scrollTo({ left: currentPage * list.offsetWidth, behavior: "smooth" });
    setActiveDot(currentPage);
}
