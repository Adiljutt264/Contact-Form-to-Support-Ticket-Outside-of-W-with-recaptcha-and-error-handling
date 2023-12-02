document.addEventListener("DOMContentLoaded", function () {
    const contactForm = document.getElementById("contactFormPreSales");

    if (contactForm) {
        contactForm.addEventListener("submit", function (e) {
            e.preventDefault(); // Prevent the default form submission.

            // Validate the message field
            const messageField = document.getElementById("message");
            const messageValue = messageField.value.trim();
            const words = messageValue.split(/\s+/).filter(Boolean); // Split the message into words

            if (messageValue.length < 2 || words.length < 2) {
                // Custom validation: At least two words or equivalent characters
                document.getElementById("resultPreSale").innerHTML = "Please enter a message with at least two words.";
                return;
            }

            // Clear any previous validation messages
            document.getElementById("resultPreSale").innerHTML = "";

            // If the validation is successful, proceed with form submission
            const formData = new FormData(contactForm);
            fetch("customContact.php", {
                method: "POST",
                body: formData,
            })
                .then((response) => {
                    if (response.ok) {
                        // Request was successful, update the result element
                        return response.text();
                    } else {
                        // Request encountered an error
                        console.error("Request failed with status: " + response.status);
                        throw new Error("Request failed");
                    }
                })
                .then((responseData) => {
                    document.getElementById("resultPreSale").innerHTML = responseData;
                    document.getElementById("contactFormPreSales").reset(); // Reset the form
                })
                .catch((error) => {
                    console.error("An error occurred:", error);
                });
        });
    }
});
