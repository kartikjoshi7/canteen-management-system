// Wait for the HTML to fully load
document.addEventListener("DOMContentLoaded", function() {

    // ---------------------------------------------------------
    // FEATURE 1: DYNAMIC "TOAST" NOTIFICATIONS 🍞
    // ---------------------------------------------------------
    // Logic: We check the URL for success flags (e.g. menu.php?added=1)
    // and show a beautiful popup message instead of static text.
    
    const urlParams = new URLSearchParams(window.location.search);
    
    if (urlParams.has('added')) {
        showToast("✅ Item added to cart successfully!");
    }
    
    // Function to generate the popup on the fly
    function showToast(message) {
        const toast = document.createElement("div");
        toast.textContent = message;
        
        // Apply "Instagram-style" popup CSS dynamically
        Object.assign(toast.style, {
            position: "fixed",
            bottom: "30px",
            right: "30px",
            background: "#333",
            color: "#fff",
            padding: "15px 25px",
            borderRadius: "50px",
            boxShadow: "0 5px 15px rgba(0,0,0,0.2)",
            zIndex: "9999",
            opacity: "0",
            transform: "translateY(20px)",
            transition: "all 0.5s ease"
        });

        document.body.appendChild(toast);

        // Animate In (Fade + Slide Up)
        setTimeout(() => {
            toast.style.opacity = "1";
            toast.style.transform = "translateY(0)";
        }, 10);

        // Animate Out after 3 seconds
        setTimeout(() => {
            toast.style.opacity = "0";
            toast.style.transform = "translateY(20px)";
            setTimeout(() => toast.remove(), 500); // Remove from HTML
        }, 3000);
    }


    // ---------------------------------------------------------
    // FEATURE 2: GLOBAL DELETE/CLEAR CONFIRMATION ⚠️
    // ---------------------------------------------------------
    // Logic: Instead of writing code for every single button, we look for 
    // ANY link that has 'delete' or 'clear' in its URL. 
    // This protects Admin delete buttons AND the Cart clear button.
    
    const dangerLinks = document.querySelectorAll("a[href*='action=clear'], a[href*='action=delete']");
    
    dangerLinks.forEach(link => {
        link.addEventListener("click", function(event) {
            const isConfirmed = confirm("Are you sure you want to perform this action? This cannot be undone.");
            if (!isConfirmed) {
                event.preventDefault(); // Stop the action
            }
        });
    });


    // ---------------------------------------------------------
    // FEATURE 3: PREVENT DOUBLE SUBMISSIONS 🚫
    // ---------------------------------------------------------
    // Logic: When you click "Place Order" or "Login", we disable the button.
    // This prevents students from accidentally ordering twice by double-clicking.
    
    const forms = document.querySelectorAll("form");
    
    forms.forEach(form => {
        form.addEventListener("submit", function() {
            const btn = form.querySelector("button[type='submit']");
            if (btn) {
                btn.disabled = true;             // Freeze the button
                btn.innerHTML = "Processing..."; // Change text
                btn.style.opacity = "0.7";       // Dim it slightly
                btn.style.cursor = "not-allowed";
            }
        });
    });


    // ---------------------------------------------------------
    // FEATURE 4: AUTO-HIDE PHP ERROR MESSAGES 👻
    // ---------------------------------------------------------
    // Logic: Finds any red error text or success messages and fades them out.
    
    const messages = document.querySelectorAll("p[style*='color: red'], .success-msg");
    
    if (messages.length > 0) {
        setTimeout(function() {
            messages.forEach(msg => {
                msg.style.transition = "opacity 1s ease";
                msg.style.opacity = "0";
                setTimeout(() => msg.style.display = "none", 1000);
            });
        }, 4000); // Wait 4 seconds before fading
    }

});