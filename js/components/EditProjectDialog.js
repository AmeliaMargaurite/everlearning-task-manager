import {
	closeModal,
	getProjectIdFromURL,
	projectFunctionsURL,
	projectRequestsURL,
} from "../helpers.js";

export class EditProjectDialog extends HTMLElement {
	constructor() {
		super();
	}

	async getProjectData(project_id) {
		const response = await fetch(projectRequestsURL, {
			method: "POST",
			body: JSON.stringify({ request: "get_project_data", project_id }),
		});
		const json = await response.json();
		return json;
	}

	async connectedCallback() {
		const project_id = getProjectIdFromURL();

		const data = await this.getProjectData(project_id);

		const form = `
        <form action="${projectFunctionsURL}" method="POST" id="modal-form">
        
          <button class="delete-link" id="delete-btn">Delete</button>
          <div>
            <label for="name">Name</label>
            <input name="name" type="text" required value="${data.name}" autofocus id="name-input"/>
          </div>
          <div class="description">
            <label for="description" >Description</label>
            <textarea name="description" required >${data.description}</textarea>
          </div>
          <input type="hidden" name="edit_project" value="${project_id}"/>
          <input type="hidden" name="project_id" value="${project_id}"/>
        </form>

    `;
		this.innerHTML = `<modal-popup id="edit-project_modal"></modal-popup>`;
		const modal = document.getElementById("modal--contents");
		modal.innerHTML = form;

		const nameInput = document.getElementById("name-input");
		nameInput.focus();

		const confirmDeleteProjectOverlay = document.createElement(
			"confirm-delete-project-overlay"
		);
		confirmDeleteProjectOverlay.setAttribute("project_id", project_id);

		const deleteBtn = document.getElementById("delete-btn");
		deleteBtn.focus = false;
		deleteBtn.onclick = (e) => {
			e.preventDefault();
			modal.appendChild(confirmDeleteProjectOverlay);
		};
	}
}

class ConfirmDeleteProjectOverlay extends HTMLElement {
	constructor() {
		super();
	}

	async deleteProject(project_id) {
		const response = await fetch(projectRequestsURL, {
			method: "POST",
			body: JSON.stringify({
				request: "delete_project",
				project_id,
			}),
		}).then((res) => res.json());
		if (response === "success") {
			location.reload();
		} else {
			console.error("Error in deleteProject: " + response);
		}
	}

	cancelDelete() {
		this.parentNode.removeChild(this);
	}

	connectedCallback() {
		const project_id = this.getAttribute("project_id");

		const div = document.createElement("div");
		div.className = "warning-overlay";

		const background = document.createElement("div");
		background.className = "overlay-background";

		const p = document.createElement("p");
		p.innerHTML =
			"Are you sure you want to delete this project? This action cannot be undone.";

		const cancelBtn = document.createElement("button");
		cancelBtn.className = "cancel-btn";
		cancelBtn.innerHTML = "Cancel";
		cancelBtn.onclick = () => this.cancelDelete();
		cancelBtn.autofocus = true;
		const deleteBtn = document.createElement("button");
		deleteBtn.className = "delete";
		deleteBtn.innerHTML = "Delete";
		deleteBtn.onclick = () => this.deleteProject(project_id);

		div.appendChild(p);
		div.appendChild(cancelBtn);
		div.appendChild(deleteBtn);
		// div.appendChild(background);

		this.append(background);
		this.append(div);
	}
}

customElements.define(
	"confirm-delete-project-overlay",
	ConfirmDeleteProjectOverlay
);
