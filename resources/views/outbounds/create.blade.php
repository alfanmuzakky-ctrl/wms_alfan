<div class="section-box">

    <div class="section-title">
        Outbound Detail
    </div>

    <form method="POST" action="/outbounds" id="addForm">
        @csrf

        <div class="form-grid">

            <div class="form-group">
                <label>ID Outbound</label>
                <input 
                    type="text"
                    name="id"
                    placeholder="OUT-0001"
                    required>
            </div>

            <div class="form-group">
                <label>Customer</label>
                <select name="customer_id" required>
                    <option value="">-- Pilih Customer --</option>

                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">
                            {{ $c->id }} - {{ $c->name }}
                        </option>
                    @endforeach

                </select>
            </div>

        </div>

        <br>

        <div class="form-actions">
            <button type="submit" class="section-button">
                Create Outbound
            </button>
        </div>

    </form>

</div>