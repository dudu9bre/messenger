import $ from "jquery"

let fadeInBtn = document.querySelectorAll(".toggle")[0]
let fadeOutBtn = document.querySelectorAll(".toggle")[1]

fadeInBtn.addEventListener("click", () => {
    $(".register").fadeIn(240)
})
fadeInBtn.addEventListener("touchend", () => {
    $(".register").fadeIn(240)
})

fadeOutBtn.addEventListener("click", () => {
    $(".register").fadeOut(240)
})
fadeOutBtn.addEventListener("touchend", () => {
    $(".register").fadeOut(240)
})

$("#login").on("submit", function(e) {

})