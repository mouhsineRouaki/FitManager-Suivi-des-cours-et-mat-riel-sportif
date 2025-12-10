


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>Document</title>
</head>

<body>
    <div id="container" class="container">
        <div class="row">

            <!-- SIGN UP -->
            <form class="col align-items-center flex-col sign-up" action="./php/signUp.php" method="POST">
                <div class="form-wrapper align-items-center">
                    <div class="form sign-up">

                        <div class="input-group">
                            <i class='bx bxs-user'></i>
                            <input type="text" name="user_name" placeholder="Username">
                        </div>

                        <div class="input-group">
                            <i class='bx bx-mail-send'></i>
                            <input type="email" name="user_email" placeholder="Email">
                        </div>

                        <div class="input-group">
                            <i class='bx bxs-lock-alt'></i>
                            <input type="password" name="user_password" placeholder="Password">
                        </div>

                        <div class="input-group">
                            <i class='bx bxs-lock-alt'></i>
                            <input type="password" placeholder="Confirm password">
                        </div>

                        <!-- AJOUT : URL image -->
                        <div class="input-group">
                            <i class='bx bxs-image'></i>
                            <input type="text" id="imageUrl" name="user_image" placeholder="Image URL">
                        </div>

                        <!-- AJOUT : Aperçu image -->
                        <div class="preview-image">
                            <img id="previewImg" src="../assets/userProfil.webp" alt="Aperçu">
                        </div>

                        <button type="submit">Sign up</button>

                        <p>
                            <span>Already have an account?</span>
                            <b onclick="toggle()" class="pointer">Sign in here</b>
                        </p>
                    </div>
                </div>
            </form>

            <!-- SIGN IN -->
            <form class="col align-items-center flex-col sign-in" action="./php/signIn.php" method="POST">
                <div class="form-wrapper align-items-center">
                    <div class="form sign-in">

                        <div class="input-group">
                            <i class='bx bxs-user'></i>
                            <input type="text" name="email" placeholder="email">
                        </div>

                        <div class="input-group">
                            <i class='bx bxs-lock-alt'></i>
                            <input type="password" name="password" placeholder="Password">
                        </div>

                        <button onclick="passer()" type="submit">Sign in</button>

                        <p><b>Forgot password?</b></p>

                        <p>
                            <span>Don't have an account?</span>
                            <b onclick="toggle()" class="pointer">Sign up here</b>
                        </p>

                    </div>
                </div>
            </form>

        </div>

        <div class="row content-row">
            <div class="col align-items-center flex-col">
                <div class="text sign-in">
                    <h2>Welcome</h2>
                </div>
                <div class="img sign-in"></div>
            </div>

            <div class="col align-items-center flex-col">
                <div class="img sign-up"></div>
                <div class="text sign-up">
                    <h2>Join with us</h2>
                </div>
            </div>
        </div>

    </div>

    <script src="./js/script.js"></script>

</body>
</html>
