<?php
view('partials/header');
?>
    <div class="d-flex align-items-center py-4 bg-body-tertiary">
        <main class="form-signin w-100 mt-5 m-auto">
            <form method="post" action="<?= url('auth/signup') ?>">
                <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

                <div class="form-floating">
                    <input type="email" class="form-control" id="floatingInput" name="email" placeholder="name@example.com">
                    <label for="floatingInput">Email address</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="pass" name="password" placeholder="Password">
                    <label for="pass">Password</label>
                </div>
                <div class="form-floating">
                    <input type="password" class="form-control" id="pass_confirm" name="password_confirm" placeholder="Confirm Password">
                    <label for="pass_confirm">Confirm password</label>
                </div>

                <button class="btn btn-primary w-100 py-2" type="submit">Sign up</button>
            </form>
        </main>
    </div>
<?php
view('partials/footer');
