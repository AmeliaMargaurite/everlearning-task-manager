import { closeModal } from "../helpers.js";

export class ModalNoButtons extends HTMLElement {
	constructor() {
		super();
	}

	connectedCallback() {
		const modalsId = this.getAttribute("id");

		this.innerHTML = `
			<modal-overlay modals_id='${modalsId}'>
        <div class="wrapper" onclick="event.stopPropagation()">
          <div id="modal--contents" class="modal--contents" ></div>
         
        </div>
			</modal-overlay>
    `;
	}
}
