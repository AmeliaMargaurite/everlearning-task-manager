import {
	getProjectIdFromURL,
	noteFunctionsURL,
} from "../helpers__1661872787419__.js";

export class AddNewNoteDialog extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const project_id = getProjectIdFromURL();

		const form = `
			<form action="${noteFunctionsURL}" method="POST" id="modal-form">
										<rich-text-editor name-to-save="note" data=""></rich-text-editor>

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
