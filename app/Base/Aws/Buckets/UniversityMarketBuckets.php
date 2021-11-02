<?php

/**
 * Definicao de buckets utilizados no S3 pelo projeto University Market
 */
abstract class UniversityMarketBuckets {

    /**
     * @property string $default Bucket padrão para armazenamento de recursos do projeto
     */
    public static $default = "university-market-bucket";
}