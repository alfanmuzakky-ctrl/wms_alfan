<div class="section-box">
    <div class="section-title">
        Customer Detail
    </div>

    <form id="addForm">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>Customer ID</label>
                <input type="text" name="id" placeholder="CUS-001">
            </div>

            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="name">
            </div>

            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email">
            </div>

            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone">
            </div>

            <div class="form-group">
                <label>Contact Person</label>
                <input type="text" name="contact_person">
            </div>

            <div class="form-group">
                <label>Contact Phone</label>
                <input type="text" name="contact_phone">
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label>Address</label>
                <textarea name="address" rows="3"></textarea>
            </div>

        </div>

        <br>

        <div class="form-actions">
            <button type="submit" class="section-button">Add Customer</button>
        </div>

    </form>
</div>