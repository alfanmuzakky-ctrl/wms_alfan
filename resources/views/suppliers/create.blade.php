<div class="section-box">
    <div class="section-title">
        Supplier Detail
    </div>

    <form id="addForm">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>ID Supplier</label>
                <input type="text" name="id" placeholder="SUP-001" required>
            </div>

            <div class="form-group">
                <label>Supplier Name</label>
                <input type="text" name="name" placeholder="Supplier Name" required>
            </div>
            <div class="form-group">
                <label>Company Name</label>
                <input type="text" name="company_name" placeholder="PT. xxxxx" required>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="0812xxxx">
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="supplier@email.com">
            </div>

            <div class="form-group" style="grid-column: span 2;">
                <label>Full Address</label>
                <textarea name="address" rows="3"></textarea>
            </div>

        </div>

        <br>

        <div class="form-actions">
            <button type="submit" class="section-button">Add Supplier</button>
        </div>

    </form>
</div>