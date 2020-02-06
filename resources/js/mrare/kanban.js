//
//
// kanban.js
//
// Initializes the kanban plugin .
//

import Draggable from '@shopify/draggable/lib/draggable';
import SwapAnimation from '@shopify/draggable/lib/plugins';
import { Sortable, Plugins } from '@shopify/draggable';

const mrKanban = {
  sortableKanbanLists: new Sortable(document.querySelectorAll('div.kanban-board'), {
    draggable: '.kanban-col:not(:last-child)',
    handle: '.card-list-header',
  }),

  sortableKanbanCards: new Sortable(document.querySelectorAll('.kanban-col .card-list-body'), {
    // plugins: [SwapAnimation],
    plugins: [Plugins.SwapAnimation],
    draggable: '.card-kanban',
    handle: '.card-kanban',
    appendTo: 'body',
  }),
};

export default mrKanban;
