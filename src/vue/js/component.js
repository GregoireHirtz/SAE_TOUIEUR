window.addEventListener('load', () => {
	let inputs = document.querySelectorAll('.input > input');
	inputs.forEach(input => {
		input.addEventListener('input', () => {
			input.setAttribute('value', input.value);
		});
	});

	let password_inputs = document.querySelectorAll('.input.password');
	password_inputs.forEach(input => {
		input.querySelector('img').addEventListener('click', (event) => {
			let el_input = input.querySelector('input');
			if (event.target.src.includes('slash')) {
				el_input.setAttribute('type', 'text');
				event.target.title = 'Cacher le mot de passe';
				event.target.src = 'src/vue/images/eye.svg';
			} else {
				el_input.setAttribute('type', 'password');
				event.target.title = 'Afficher le mot de passe';
				event.target.src = 'src/vue/images/eye-slash.svg';
			}
		});
	});

	let abonne_button = document.querySelectorAll('.sabonner');
	abonne_button.forEach(button => {
		button.addEventListener('click', () => {
			if (button.classList.contains('de')) {
				button.classList.remove('de');
				button.innerText = 'S\'abonner';
			} else {
				button.classList.add('de');
				button.innerText = "Se désabonner";
			}
		});
	});

	let vote_buttons = document.querySelectorAll('article > footer > :first-child > img');
	vote_buttons.forEach(button => {
		button.addEventListener('click', () => {
			if (button.src.includes('full')) {
				button.src = button.src.replace('full', 'empty');
			} else {
				button.src = button.src.replace('empty', 'full');
				if (button.parentElement.firstElementChild === button)
					button.parentElement.lastElementChild.src = button.parentElement.lastElementChild.src.replace('full', 'empty');
				else
					button.parentElement.firstElementChild.src = button.parentElement.firstElementChild.src.replace('full', 'empty');
			}
		});
	});
});


function switchLogMethod(form, switchTo) {
	form.style.display = 'none';
	if (switchTo === 'connect')
		form.previousElementSibling.style.display = 'flex';
	else
		form.nextElementSibling.style.display = 'flex';
}