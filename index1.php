<!DOCTYPE html>
<meta charset="utf-8">

<head>
    <script type="text/javascript" src="JavaScript/dashboard.js"></script>
    <link rel="stylesheet" href="CSS/dashboard.css">
    <?php include_once 'DbConnection.php'; ?>
</head>
<body>

<div id='dashboard'>

</div>

<script>
var freqData=[
    {State:'Gen',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 1, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 1, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 1, 2016); ?>}}
    ,{State:'Feb',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 2, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 2, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 2, 2016); ?>}}
    ,{State:'Mar',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 3, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 3, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 3, 2016); ?>}}
    ,{State:'Apr',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 4, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 4, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 4, 2016); ?>}}
    ,{State:'Mag',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 5, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 5, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 5, 2016); ?>}}
    ,{State:'Giu',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 6, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 6, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 6, 2016); ?>}}
    ,{State:'Lug',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 7, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 7, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 7, 2016); ?>}}
    ,{State:'Ago',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 8, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 8, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 8, 2016); ?>}}
    ,{State:'Set',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 9, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 9, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 9, 2016); ?>}}
    ,{State:'Ott',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 10, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 10, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 10, 2016); ?>}}
    ,{State:'Nov',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 11, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 11, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 11, 2016); ?>}}
    ,{State:'Dic',freq:{positivo:<?php echo getFrequency(DbConnection(), 'pos', 12, 2016); ?>,
            neutrale:<?php echo getFrequency(DbConnection(), 'neu', 12, 2016); ?>,
            negativo:<?php echo getFrequency(DbConnection(), 'neg', 12, 2016); ?>}}
];

dashboard('#dashboard',freqData);

</script>

