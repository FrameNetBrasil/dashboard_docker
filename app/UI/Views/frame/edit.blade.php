<form hx-put="/frame/save" hx-target="this" hx-swap="outerHTML">
    <div>
        <label>First Name</label>
        <input type="text" name="firstName" value="Joe">
    </div>
    <div class="form-group">
        <label>Last Name</label>
        <input type="text" name="lastName" value="Blow">
    </div>
    <div class="form-group">
        <label>Email Address</label>
        <input type="email" name="email" value="joe@blow.com">
    </div>
    <button class="btn">Submit</button>
    <q-btn hx-get="/frame/main">Cancel</q-btn>
</form>
<script>
    console.log('after request');
</script>
