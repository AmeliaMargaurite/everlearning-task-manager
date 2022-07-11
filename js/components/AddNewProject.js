import { BASE_URL, projectFuctionsURL } from "../helpers.js";

export class AddNewProjectButton extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const handleClick = () => {
			const dialog = document.createElement("add-new-project-dialog");
			const body = document.body;
			body.appendChild(dialog);
		};

		const button = document.createElement("button");
		button.id = "add_new_project_button";
		button.innerText = "Add New Project";
		button.onclick = handleClick;

		this.append(button);
	}
}

class AddNewProjectDialog extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const form = `
			<form action="${projectFuctionsURL}" method="POST" id="modal-form">
				<div>		
					<label for="name">Name</label>
					<input name="name" type="text" required autofocus id="name-input" /></div>
				<div class="description">
					<label for="description">Description</label>
					<textarea name="description" required></textarea>
				</div>
				<input type="hidden" name="save_new_project" value="save_new_project"/>
					
			</form>
    `;

		this.innerHTML = `<modal-popup id="add-new-project_modal"></modal-popup>`;
		const modal = document.getElementById("modal--contents");
		modal.innerHTML = form;

		const nameInput = document.getElementById("name-input");
		nameInput.focus();
	}
}

customElements.define("add-new-project-dialog", AddNewProjectDialog);
