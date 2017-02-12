<?php
/**
 * Created by PhpStorm.
 * User: To Olympus
 * Date: 2017-02-11
 * Time: 6:00 PM
 */

?>


<div class="row">
    <h2>Assemblies</h2>
    <table>
        <tr>
            <td>Assembly ID</td>
            <td>Date</td>
            <td>Robot ID</td>
        </tr>
        {assembly}
        <tr>
            <td>{assemblyID}</td>
            <td>{date}</td>
            <td>{robotID}</td>
        </tr>
        {/assembly}
    </table>
</div>

<div class="row">
    <h2>Shipments</h2>
    <table>
        <tr>
            <td>Shipments ID</td>
            <td>Date</td>
            <td>Robot ID</td>
        </tr>
        {shipment}
        <tr>
            <td>{shipmentID}</td>
            <td>{date}</td>
            <td>{robotID}</td>
        </tr>
        {/shipment}
    </table>
</div>

<div class="row">
    <h2>Purchases</h2>
    <table>
        <tr>
            <td>Purchase ID</td>
            <td>Date</td>
            <td>Part ID</td>
        </tr>
        {purchase}
        <tr>
            <td>{purchaseID}</td>
            <td>{date}</td>
            <td>{partID}</td>
        </tr>
        {/purchase}
    </table>
</div>

<div class="row">
    <h2>Returns</h2>
    <table>
        <tr>
            <td>Return ID</td>
            <td>Date</td>
            <td>Part ID</td>
        </tr>
        {return}
        <tr>
            <td>{returnID}</td>
            <td>{date}</td>
            <td>{partID}</td>
        </tr>
        {/return}
    </table>
</div>

