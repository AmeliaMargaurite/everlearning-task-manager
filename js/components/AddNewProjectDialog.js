import { projectFunctionsURL } from "../helpers.js";

export class AddNewProjectDialog extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const form = `
			<form action="${projectFunctionsURL}" method="POST" id="modal-form">
				<div>		
					<label for="name">Name</label>
					<input name="name" type="text" required autofocus id="name-input" /></div>
				<div class="description">
					<label for="description">Description</label>
						<rich-text-editor name-to-save="description" data=""></rich-text-editor>
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
