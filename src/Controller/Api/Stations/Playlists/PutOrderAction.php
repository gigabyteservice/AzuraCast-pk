<?php

declare(strict_types=1);

namespace App\Controller\Api\Stations\Playlists;

use App\Entity;
use App\Exception;
use App\Http\Response;
use App\Http\ServerRequest;
use Psr\Http\Message\ResponseInterface;

class PutOrderAction extends AbstractPlaylistsAction
{
    public function __invoke(
        ServerRequest $request,
        Response $response,
        Entity\Repository\StationPlaylistMediaRepository $playlistMediaRepository,
        int $id
    ): ResponseInterface {
        $record = $this->requireRecord($request->getStation(), $id);

        if (
            Entity\Enums\PlaylistSources::Songs !== $record->getSourceEnum()
            || Entity\Enums\PlaylistOrders::Sequential !== $record->getOrderEnum()
        ) {
            throw new Exception(__('This playlist is not a sequential playlist.'));
        }

        $order = $request->getParam('order');

        $playlistMediaRepository->setMediaOrder($record, $order);
        return $response->withJson($order);
    }
}
