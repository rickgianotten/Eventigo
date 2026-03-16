const tickets = [];

function createTicket(){
    const ticket = document.createElement("div")
    ticket.innerHTML = `
        <div class="border border-light-grey/20 rounded-md p-4 space-y-5">
            <x-form.select name="'tickets['+index+'][type]'"  label="Ticket type">
                <option value="Regular">Regular</option>
                <option value="VIP">VIP</option>
            </x-form.select>

            <x-form.form-group type="number" name="'tickets['+index+'][price]'" label="Price ($)" placeholder="75,00"/>

            <x-form.form-group type="number"  name="'tickets['+index+'][quantity_available]'" label="Quantity available" placeholder="100"/>

            <x-form.form-group type="text" name="'tickets['+index+'][description]'" label="Description (optional)" placeholder=" e.g. Inc. food & drinks" :required="false"/>
                                        
        </div>
        `
}