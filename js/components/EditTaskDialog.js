import {
	closeModal,
	getProjectIdFromURL,
	taskFunctionsURL,
	taskRequestURL,
} from "../helpers.js";

export class EditTaskDialog extends HTMLElement {
	constructor() {
		super();
	}

	async getTaskData(task_id, project_id) {
		const response = await fetch(taskRequestURL, {
			method: "POST",
			body: JSON.stringify({ request: "get_task_data", task_id, project_id }),
		});
		const json = await response.json();
		return json;
	}

	async connectedCallback() {
		const project_id = getProjectIdFromURL();
		const task_id = this.getAttribute("task_id");

		const data = await this.getTaskData(task_id, project_id);

		const form = `
        <form action="${taskFunctionsURL}" method="POST" id="modal-form">
        
          <button class="delete-link" id="delete-btn">Delete</button>
          <div>
            <label for="name">Name</label>
            <input name="name" type="text" required value="${data.name}" autofocus id="name-input"/>
          </div>
          <div class="description">
            <label for="description" >Description</label>
            <textarea name="description" required >${data.description}</textarea>
          </div>
					<div class="settings">
						<span>
							
							<category-dropdown task_id="${task_id}"></category-dropdown>
						</span>
						<span>
							<due-date task_id="${task_id}"></due-date>
						</span>
						<span>
							<priority-dropdown task_id="${task_id}"></priority-dropdown>
						</span>
					</div>
					
          <input type="hidden" name="edit_task" value="${project_id}"/>
          <input type="hidden" name="task_id" value="${task_id}"/>
        </form>

    `;
		this.innerHTML = `<modal-popup id="edit-task_modal"></modal-popup>`;
		const modal = document.getElementById("modal--contents");
		modal.innerHTML = form;

		const nameInput = document.getElementById("name-input");
		nameInput.focus();

		const confirmDeleteOverlay = document.createElement(
			"confirm-delete-overlay"
		);
		confirmDeleteOverlay.setAttribute("task_id", task_id);
		confirmDeleteOverlay.setAttribute("project_id", project_id);
		// confirmDeleteOverlay.set;
		const deleteBtn = document.getElementById("delete-btn");
		deleteBtn.focus = false;
		deleteBtn.onclick = (e) => {
			e.preventDefault();
			modal.appendChild(confirmDeleteOverlay);
		};
	}
}

class ConfirmDeleteOverlay extends HTMLElement {
	constructor() {
		super();
	}

	async deleteTask(task_id, project_id) {
		const response = await fetch(taskRequestURL, {
			method: "POST",
			body: JSON.stringify({ request: "delete_task", task_id, project_id }),
		}).then((res) => res.json());
		if (response === "success") {
			location.reload();
		} else {
			console.error("Error in deleteTask: " + response);
		}
	}

	cancelDelete() {
		this.parentNode.removeChild(this);
	}

	connectedCallback() {
		const task_id = this.getAttribute("task_id");
		const project_id = this.getAttribute("project_id");

		const div = document.createElement("div");
		div.className = "warning-overlay";

		const background = document.createElement("div");
		background.className = "overlay-background";

		const p = document.createElement("p");
		p.innerHTML =
			"Are you sure you want to delete this task? This action cannot be undone.";

		const cancelBtn = document.createElement("button");
		cancelBtn.className = "cancel-btn";
		cancelBtn.innerHTML = "Cancel";
		cancelBtn.onclick = () => this.cancelDelete();
		cancelBtn.autofocus = true;
		const deleteBtn = document.createElement("button");
		deleteBtn.className = "delete";
		deleteBtn.innerHTML = "Delete";
		deleteBtn.onclick = () => this.deleteTask(task_id, project_id);

		div.appendChild(p);
		div.appendChild(cancelBtn);
		div.appendChild(deleteBtn);
		// div.appendChild(background);

		this.append(background);
		this.append(div);
	}
}

customElements.define("confirm-delete-overlay", ConfirmDeleteOverlay);
