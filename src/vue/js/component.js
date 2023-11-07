window.addEventListener('load', () => {
    let inputs = document.querySelectorAll('.input > input');
    console.log(inputs);
    inputs.forEach(input => {
        input.addEventListener('input', () => {
            input.setAttribute('value', input.value);
        });
    });
});