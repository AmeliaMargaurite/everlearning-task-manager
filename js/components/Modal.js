import { closeModal } from "../helpers.js";

export class Modal extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const modalsId = this.getAttribute("id");

		this.innerHTML = `
			<modal-overlay modals_id='${modalsId}'>
        <div class="wrapper" onclick="event.stopPropagation()">
          <div id="modal--contents" class="modal--contents" ></div>
          <button type="cancel" id="cancel-btn">Cancel</button>
          <button type="submit" form="modal-form" class="special">Save</button>

        </div>
			</modal-overlay>
    `;

		const cancelBtn = document.getElementById("cancel-btn");
		cancelBtn.onclick = () => closeModal(modalsId);
	}
}
