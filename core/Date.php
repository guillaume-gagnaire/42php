<?php

class                           Date {
    public static function      month($nb) {
        $months = [
            _t("Janvier"),
            _t("Février"),
            _t("Mars"),
            _t("Avril"),
            _t("Mai"),
            _t("Juin"),
            _t("Juillet"),
            _t("Août"),
            _t("Septembre"),
            _t("Octobre"),
            _t("Novembre"),
            _t("Décembre")
        ];

        return $months[intval($nb)];
    }

    public static function      toFr($date) {
        if (!is_int($date))
            $date = strtotime($date);

        return implode(' ', [
            date('d', $date),
            self::month(intval(date('n', $date)) - 1),
            date('Y', $date)
        ]);
    }
}

?>