import { getProjectIdFromURL, noteFunctionsURL } from "../helpers.js";

export class AddNewNoteIcon extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const handleClick = () => {
			const dialog = document.createElement("add-new-note-dialog");
			const body = document.body;
			body.appendChild(dialog);
		};
		const span = document.createElement("span");
		const icon = document.createElement("div");
		icon.className = "icon plus";
		icon.onclick = handleClick;

		span.appendChild(icon);
		this.append(span);
	}
}

class AddNewNoteDialog extends HTMLElement {
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

customElements.define("add-new-note-dialog", AddNewNoteDialog);
