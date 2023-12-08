function validateNumericInput(inputElement) {
    // Remove any non-numeric characters except the dot (.) and minus sign (-)
    inputElement.value = inputElement.value.replace(/[^0-9.-]/g, '');

    // Ensure there is at most one dot
    let dotCount = (inputElement.value.match(/\./g) || []).length;
    if (dotCount > 1) {
        // If there is more than one dot, remove the last one
        inputElement.value = inputElement.value.slice(0, -1);
    }

    // Ensure there is at most one minus sign at the beginning
    let minusCount = (inputElement.value.match(/-/g) || []).length;
    if (minusCount > 1 || inputElement.value.indexOf('-') !== 0) {
        // If there is more than one minus sign or not at the beginning, remove the last one
        inputElement.value = inputElement.value.replace(/-/g, '');
    }
}