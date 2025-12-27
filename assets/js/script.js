// Wait for the HTML to fully load before running the script
// Que: "Why use 'DOMContentLoaded'?"
// Ans: "JavaScript loads faster than HTML images. If we try to find a button before 
// it exists on the screen, the code will crash. This listener ensures we wait until the page is ready."
document.addEventListener("DOMContentLoaded", function() {

    // feature 1: CONFIRMATION BEFORE CLEARING CART
    // We look for any link that has 'action=clear' in its address (URL)
    const clearCartBtn = document.querySelector("a[href*='action=clear']");
    
    if (clearCartBtn) { 
        // When that click happens, it triggers my custom function immediately."
        clearCartBtn.addEventListener("click", function(event) {
            
            // Show a browser popup asking Yes/No
            const isConfirmed = confirm("Are you sure you want to empty your cart?");
            
            if (!isConfirmed) {
                // CRITICAL LOGIC: 
                // If user clicks 'Cancel', we use preventDefault() to STOP the link 
                // from actually refreshing the page. The cart stays safe.
                event.preventDefault();
            }
        });
    }

    // feature 2: AUTO-HIDE ERROR MESSAGES
    // We look for the error message on the login page (red text)
    const errorMessage = document.querySelector("p[style*='color: red']");
    
    if (errorMessage) {
        // Logic: Wait for 3 seconds (3000ms), then fade it out.
        // This improves User Experience (UX) by keeping the interface clean.
        setTimeout(function() {
            errorMessage.style.transition = "opacity 0.5s"; // Smooth fade effect
            errorMessage.style.opacity = "0"; // Make it invisible
            
            // After fade out is done (0.5s later), remove it strictly from the page layout
            setTimeout(() => errorMessage.style.display = "none", 500);
        }, 3000);
    }

});