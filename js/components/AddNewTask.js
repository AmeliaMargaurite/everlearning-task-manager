import { getProjectIdFromURL, taskFunctionsURL } from "../helpers.js";

export class AddNewTaskIcon extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const handleClick = () => {
			const dialog = document.createElement("add-new-task-dialog");
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

class AddNewTaskDialog extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const project_id = getProjectIdFromURL();
		const form = `
			<form action="${taskFunctionsURL}" method="POST" id="modal-form">
				<div>
					<label for="name">Name</label>
					<input name="name" type="text" required autofocus tabindex="0" id="name-input" />
				</div>
				
				<div class="description">
					<label for="description">Description</label>
					<textarea name="description" required tabindex="0" ></textarea>
				</div>

				<div class="settings">
						<span>
							<category-dropdown task_id="NULL"></category-dropdown>
						</span>
						<span>
							<due-date task_id="NULL"></due-date>
						</span>
						<span>
							<priority-dropdown task_id="NULL"></priority-dropdown>
						</span>
					</div>
				<input type="hidden" name="save_new_task" value="${project_id}"/>
			</form>`;

		this.innerHTML = `<modal-popup id="add-new-task"></modal-popup>`;
		const modal = document.getElementById("modal--contents");

		modal.innerHTML = form;

		const nameInput = document.getElementById("name-input");
		nameInput.focus();
	}
}

customElements.define("add-new-task-dialog", AddNewTaskDialog);
