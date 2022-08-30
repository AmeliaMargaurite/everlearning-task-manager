import {
	closeModal,
	getProjectIdFromURL,
	noteFunctionsURL,
	noteRequestsURL,
} from "../helpers__1661878736011__.js";

export class EditNoteDialog extends HTMLElement {
	constructor() {
		super();
	}

	async getNoteData(note_id, project_id) {
		const response = await fetch(noteRequestsURL, {
			method: "POST",
			body: JSON.stringify({ request: "get_note_data", note_id, project_id }),
		});
		const json = await response.json();
		return json;
	}

	async connectedCallback() {
		const project_id = getProjectIdFromURL();
		const note_id = this.getAttribute("note_id");

		const data = await this.getNoteData(note_id, project_id);

		const form = `
        <form action="${noteFunctionsURL}" method="POST" id="modal-form">
		
          <button class="btn delete-link" id="delete-btn" type="button">Delete</button>
					<label for="note">Note</label>
					<rich-text-editor name-to-save="note" data="${data?.note}"></rich-text-editor>

          
					
          <input type="hidden" name="edit_note" value="${project_id}"/>
          <input type="hidden" name="note_id" value="${note_id}"/>
        </form>

    `;
		this.innerHTML = `<modal-popup id="edit-note_modal"></modal-popup>`;
		const modal = document.getElementById("modal--contents");
		modal.innerHTML = form;
		modal.parentNode.classList.add("note");

		const textarea = document.getElementById("note");
		textarea.focus();

		const confirmDeleteNoteOverlay = document.createElement(
			"confirm-delete-note-overlay"
		);
		confirmDeleteNoteOverlay.setAttribute("note_id", note_id);
		confirmDeleteNoteOverlay.setAttribute("project_id", project_id);
		// confirmDeleteNoteOverlay.set;
		const deleteBtn = document.getElementById("delete-btn");
		deleteBtn.focus = false;
		deleteBtn.onclick = (e) => {
			e.preventDefault();
			modal.appendChild(confirmDeleteNoteOverlay);
		};
	}
}

class ConfirmDeleteNoteOverlay extends HTMLElement {
	constructor() {
		super();
	}

	async deleteNote(note_id, project_id) {
		const response = await fetch(noteRequestsURL, {
			method: "POST",
			body: JSON.stringify({ request: "delete_note", note_id, project_id }),
		}).then((res) => res.json());
		if (response === "success") {
			location.reload();
		} else {
			console.error("Error in deleteNote: " + response);
		}
	}

	cancelDelete() {
		this.parentNode.removeChild(this);
	}

	connectedCallback() {
		const note_id = this.getAttribute("note_id");
		const project_id = this.getAttribute("project_id");
		const div = document.createElement("div");
		div.className = "warning-overlay";

		const background = document.createElement("div");
		background.className = "overlay-background";

		const p = document.createElement("p");
		p.innerHTML =
			"Are you sure you want to delete this note? This action cannot be undone.";

		const cancelBtn = document.createElement("button");
		cancelBtn.className = "cancel-btn";
		cancelBtn.innerHTML = "Cancel";
		cancelBtn.onclick = () => this.cancelDelete();
		cancelBtn.autofocus = true;

		const deleteBtn = document.createElement("button");
		deleteBtn.className = "delete";
		deleteBtn.innerHTML = "Delete";
		deleteBtn.onclick = () => this.deleteNote(note_id, project_id);

		div.appendChild(p);
		div.appendChild(cancelBtn);
		div.appendChild(deleteBtn);
		// div.appendChild(background);

		this.append(background);
		this.append(div);
	}
}

customElements.define("confirm-delete-note-overlay", ConfirmDeleteNoteOverlay);
