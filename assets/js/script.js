// Wait for the HTML to fully load before running the script
document.addEventListener("DOMContentLoaded", function() {

    // feature 1: CONFIRMATION BEFORE CLEARING CART
    // We look for any link that has 'action=clear' in its address
    const clearCartBtn = document.querySelector("a[href*='action=clear']");
    
    if (clearCartBtn) {
        clearCartBtn.addEventListener("click", function(event) {
            // Show a browser popup asking Yes/No
            const isConfirmed = confirm("Are you sure you want to empty your cart?");
            
            if (!isConfirmed) {
                // If user clicks 'Cancel', stop the link from working
                event.preventDefault();
            }
        });
    }

    // feature 2: AUTO-HIDE ERROR MESSAGES
    // We look for the error message on the login page (red text)
    const errorMessage = document.querySelector("p[style*='color: red']");
    
    if (errorMessage) {
        // Wait for 3 seconds (3000ms), then fade it out
        setTimeout(function() {
            errorMessage.style.transition = "opacity 0.5s";
            errorMessage.style.opacity = "0";
            
            // After fade out is done, remove it effectively from the page layout
            setTimeout(() => errorMessage.style.display = "none", 500);
        }, 3000);
    }

});