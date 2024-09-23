<?php
/*
 * Clase CPreviousOnline (Prueba)
 */
class CPreviousOnline
{
    public function getClients () 
    {
        $db = new PgsqlQueries;
        $db->setTable("'general'.casac_clientes C");
        $db->setJoin("INNER JOIN 'general'.casag_licencias L ON C.\"id_cliente\" = L.\"id_cliente\"");
        $db->setJoin("INNER JOIN 'general'.casag_licenciasistema LS ON L.\"id_licencia\" = LS.\"id_licencia\" AND LS.\"id_sistema\" = 2");
        $db->setFields(array(
            "C.'id_cliente'",
            "C.'nombre_cliente'",
        ));
        
        $db->setParameters("TRUE");
        $db->setReturnType(PgsqlQueries::TYPE_OBJECT_ALL);
        
        $clients = $db->query(); 
        return $clients;
    }
}

?>