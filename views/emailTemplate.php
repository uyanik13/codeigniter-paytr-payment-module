<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<!------ Include the above in your HEAD tag ---------->


<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="row p-5">
                        <div class="col-md-6">
                            <img src="https://cksosyal.com/assets/uploads/user1/4e95406a02f3983833d4ca5c01583d69.png">
                        </div>

                        <div class="col-md-6 text-right">
                            <p class="font-weight-bold mb-1">Fatura No: <?php echo $transaction_id; ?></p>
                            <p class="text-muted">Fatura Tarihi: <?php echo $date; ?></p>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="row pb-5 p-5">
                        <div class="col-md-6">
                            <p class="font-weight-bold mb-4">Müşteri Bilgileri</p>
                            <p class="mb-1"><?php echo $fullname; ?></p>
                            <p>Firma:<?php echo $firma_ismi; ?></p>
                            <p class="mb-1"><?php echo $adress; ?></p>
                            <p class="mb-1">Telefon : </p>
                        </div>

                        <div class="col-md-6 text-right">
                            <p class="font-weight-bold mb-4">Fatura Bilgileri</p>
                            <p class="mb-1"><span class="text-muted">Vergi Dairesi: </span> <?php echo $vergi_dairesi; ?></p>
                            <p class="mb-1"><span class="text-muted">Vergi No: </span> <?php echo $vergi_numarasi; ?></p>
                            <p class="mb-1"><span class="text-muted">Ödeme Tipi: </span> <?php echo $type ; ?></p>
                            <p class="mb-1"><span class="text-muted">Telefon: </span> <?php echo $phone_number; ?></p>
                        </div>
                    </div>

                    <div class="row p-5">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th class="border-0 text-uppercase small font-weight-bold">NO</th>
                                    <th class="border-0 text-uppercase small font-weight-bold">PAKET</th>
                                    <th class="border-0 text-uppercase small font-weight-bold">PLAN</th>
                                    <th class="border-0 text-uppercase small font-weight-bold">ÜCRET</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td><?php echo $transaction_id; ?></td>
                                    <td><?php echo $package; ?></td>
                                    <td><?php echo $plan ;?></td>
                                    <td><?php echo $amount; ?> ₺</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex flex-row-reverse bg-dark text-white p-4">
                        <div class="py-3 px-5 text-right">
                            <div class="mb-2"></div>
                            <div class="h2 font-weight-light"></div>
                        </div>

                        <div class="py-3 px-5 text-right">
                            <div class="mb-2"></div>
                            <div class="h2 font-weight-light"></div>
                        </div>

                        <div class="py-3 px-5 text-right">
                            <div class="mb-2">Toplam</div>
                            <div class="h2 font-weight-light"><?php echo $amount; ?> ₺</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="text-light mt-5 mb-5 text-center small">by : <a class="text-light" target="_blank" href="https://cksosyal.com/">CKDIJITAL</a></div>

</div>


