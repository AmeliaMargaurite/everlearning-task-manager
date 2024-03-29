import {
	getProjectIdFromURL,
	taskFunctionsURL,
} from "../helpers__1662731975950__.js";

export class AddNewTaskDialog extends HTMLElement {
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
						<rich-text-editor name-to-save="description" data=""></rich-text-editor>
				</div>

				<div class="settings">
						<span>
							<category-dropdown task_id="" project_id="${project_id}"></category-dropdown>
						</span>
						<span>
							<due-date task_id=""></due-date>
						</span>
						<span>
							<priority-dropdown task_id=""></priority-dropdown>
						</span>
						<span>
							<days-allocated-to task_id=""></days-allocated-to>
						</span>
						<span>
							<status-dropdown data-project_id="${project_id}" data-task_id="">
							</status-dropdown>
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
