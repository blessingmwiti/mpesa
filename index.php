<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-10 m-auto text-center">
                <form action="process-payment" method="post">
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label" for="">Enter phone number</label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" name="phone" placeholder="0722000000" required
                                class="form-control w-75">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label" for="">Price</label>
                        </div>
                        <div class="col-md-10">
                            <input type="number" name="amount" placeholder="15" required class="form-control w-75">
                        </div>
                        <div class="col-md-12">
                            <input type="submit" name="submit" class="form-submit mt-2 btn btn-success">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>