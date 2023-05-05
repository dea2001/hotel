// Submit Review Form
const reviewForm = document.getElementById("review-form");
reviewForm.addEventListener("submit", (e) => {
    e.preventDefault();

    const userId = document.querySelector("input[name='user-id']").value;
    const rating = document.getElementById("rating").value;
    const comment = document.getElementById("comment").value;

    // Clear the form after submission
    reviewForm.reset();
});
