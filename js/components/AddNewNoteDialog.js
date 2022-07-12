import { getProjectIdFromURL, noteFunctionsURL } from "../helpers.js";

export class AddNewNoteDialog extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const project_id = getProjectIdFromURL();

		const form = `
			<form action="${noteFunctionsURL}" method="POST" id="modal-form">
				<textarea id="note" name="note"></textarea>
				<input type="hidden" name="save_new_note" value="${project_id}"/>
			</form>`;

		this.innerHTML = `<modal-popup id="add-new-note"></modal-popup>`;
		const modal = document.getElementById("modal--contents");
		modal.parentNode.classList.add("note");

		modal.innerHTML = form;

		const textarea = document.getElementById("note");
		textarea.focus();
	}
}
