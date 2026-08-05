<h1>PhyloTree Nested Set Engine Specification</h1>
<blockquote>
<strong>Document Status</strong><br>

Version: 1.0 Draft<br>
Status: In Development<br>
Specification Type: Technical Design Specification<br>
Target: PhyloTree Engine

</blockquote>
<hr>
<h2>Document Rules</h2>
<ul>
    <li>This document is the primary technical specification of the PhyloTree Nested Set Engine.</li>
    <li>Every implementation must follow this specification.</li>
    <li>The specification is updated before the implementation.</li>
    <li>Documentation drives implementation.</li>
    <li>Each primitive has a single responsibility.</li>
    <li>Primitive operations are implementation-independent.</li>
</ul>
<hr>
<h2>Table of Contents</h2>
<ol>
    <li>Design Goals</li>
    <li>Introduction</li>
    <li>Design Principles</li>
    <li>Architecture</li>
    <li>Primitive Operations
        <ol>
            <li>Transaction Primitives</li>
            <li>Validation Primitives</li>
            <li>Lock Primitives</li>
            <li>Gap Primitives</li>
            <li>Node Primitives</li>
            <li>Query Primitives</li>
            <li>Refresh Primitives</li>
            <li>Utility Primitives</li>
        </ol>
    </li>
    <li>High-Level Operations</li>
    <li>SQL Algorithms</li>
    <li>Complexity Analysis</li>
    <li>Future Extensions</li>
</ol>
<hr>
<h2>Design Goals</h2>
<ol>
    <li>Database-agnostic algorithms</li>
    <li>Reusable primitives</li>
    <li>Minimal SQL duplication</li>
    <li>Transaction safety</li>
    <li>High scalability</li>
    <li>Deterministic behavior</li>
</ol>
<hr>
<h2>Introduction</h2>
<p>The PhyloTree Nested Set Engine is a general-purpose hierarchical data management engine based on the Nested Set model.</p>
<p>Its primary goal is to efficiently manage extremely large hierarchical structures, ranging from thousands to several million nodes, while providing a consistent API for multiple storage backends such as Doctrine, InMemory and future implementations.</p>
<p>The engine is designed around reusable algorithmic primitives rather than storage-specific implementations. This approach allows every supported storage backend to expose identical behaviour while remaining independent from its underlying persistence technology.</p>
<hr>
<h2>Design Principles</h2>
<ul>
    <li>The <code>Tree</code> class never contains database logic.</li>
    <li>All write operations are executed inside a transaction.</li>
    <li>Every write operation begins with validation.</li>
    <li>Locking is mandatory before structural modifications.</li>
    <li>Only Gap primitives are allowed to modify <code>lft</code> and <code>rgt</code> values.</li>
    <li>High-level operations are composed exclusively from primitive operations.</li>
    <li>Primitive operations must remain independently testable.</li>
    <li>Primitive operations must not depend on storage implementation details.</li>
    <li>Read-only primitives must never modify the tree structure.</li>
    <li>The engine must remain database-independent.</li>
</ul>
<hr>
<h2>Architecture</h2>

```text
                         Tree
                          │
                          ▼
              TreeStorageInterface
                          │
        ┌─────────────────┴─────────────────┐
        │                                   │
        ▼                                   ▼
DoctrineTreeStorage              InMemoryTreeStorage
        │                                   │
        └─────────────────┬─────────────────┘
                          ▼
                Nested Set Engine
                          │
     ┌────────────────────┼────────────────────┐
     │                    │                    │
Transactions         Validation            Locking
     │                    │                    │
     ├────────────────────┼────────────────────┤
     │                    │                    │
     ▼                    ▼                    ▼
Gap Operations      Node Operations     Query Operations
     │                    │                    │
     └────────────────────┼────────────────────┘
                          ▼
                  Storage Backend
```
<p>The <strong>Nested Set Engine</strong> represents the core of the system.</p>
<p>The storage implementation (Doctrine, InMemory or future backends) is responsible only for executing the primitive operations defined by this specification.</p>
<p>High-level tree operations never manipulate the storage directly. Instead, they are composed from reusable primitive operations provided by the engine.</p>
<hr>
<h2>Primitive Operations</h2>

<p>
Primitive operations are the fundamental building blocks of the PhyloTree Nested Set Engine.
Each primitive has a single responsibility and performs exactly one well-defined task.
High-level tree operations are composed exclusively from these primitives.
</p>

<p>
Primitive operations are implementation-independent.
The storage backend is responsible for providing the actual implementation while preserving the behavior defined by this specification.
</p>

<p>
Primitive operations must never invoke other primitive operations directly.
Only high-level operations are responsible for orchestrating multiple primitives into a complete workflow.
</p>

<hr>

<h3>Transaction Primitives</h3>

<p>
Transaction primitives control the lifecycle of write operations.
They guarantee atomicity and ensure that the tree always remains in a consistent state.
</p>

<h4>beginTransaction()</h4>

<h5>Purpose</h5>
<p>Starts a new database transaction before any structural modification is performed.</p>

<h5>Responsibility</h5>
<p>Creates a transactional context for all subsequent write operations.</p>

<h5>Input</h5>
<p>None.</p>

<h5>Output</h5>
<p>None.</p>

<h5>Preconditions</h5>
<ul>
    <li>A valid storage connection must be available.</li>
    <li>No conflicting transaction is active.</li>
</ul>

<h5>Postconditions</h5>
<ul>
    <li>A new transaction is active.</li>
    <li>Subsequent write operations execute inside the same transaction.</li>
</ul>

<h5>Exceptions</h5>
<ul>
    <li>TreeStorageException when the transaction cannot be started.</li>
</ul>

<h5>Implementation Notes</h5>
<p>The implementation is storage-specific.</p>
<p>Doctrine-based implementations delegate this operation to the DBAL transaction API.</p>

<h5>Execution Notes</h5>
<ul>
    <li>Must be the first primitive executed by every write operation.</li>
    <li>Must never modify the tree structure.</li>
</ul>

<h5>Used By</h5>
<ul>
    <li>appendChild()</li>
    <li>insertBefore()</li>
    <li>insertAfter()</li>
    <li>moveNode()</li>
    <li>moveSubtree()</li>
    <li>deleteNode()</li>
    <li>deleteSubtree()</li>
    <li>makeRoot()</li>
    <li>mergeTree()</li>
    <li>importTree()</li>
</ul>

<hr>

<h4>commit()</h4>

<h5>Purpose</h5>
<p>Commits the active transaction and permanently stores all pending modifications.</p>

<h5>Responsibility</h5>
<p>Finalizes a successful write operation.</p>

<h5>Input</h5>
<p>None.</p>

<h5>Output</h5>
<p>None.</p>

<h5>Preconditions</h5>
<ul>
    <li>An active transaction exists.</li>
    <li>All write operations have completed successfully.</li>
</ul>

<h5>Postconditions</h5>
<ul>
    <li>All modifications become permanent.</li>
    <li>The active transaction is closed.</li>
</ul>

<h5>Exceptions</h5>
<ul>
    <li>TreeStorageException when the transaction cannot be committed.</li>
</ul>

<h5>Implementation Notes</h5>
<p>The implementation is storage-specific.</p>

<h5>Execution Notes</h5>
<ul>
    <li>Must be the final primitive executed by a successful write operation.</li>
    <li>Must never modify the tree structure directly.</li>
</ul>

<h5>Used By</h5>
<ul>
    <li>appendChild()</li>
    <li>insertBefore()</li>
    <li>insertAfter()</li>
    <li>moveNode()</li>
    <li>moveSubtree()</li>
    <li>deleteNode()</li>
    <li>deleteSubtree()</li>
    <li>makeRoot()</li>
    <li>mergeTree()</li>
    <li>importTree()</li>
</ul>

<hr>

<h4>rollback()</h4>

<h5>Purpose</h5>
<p>Cancels the active transaction and restores the previous consistent state.</p>

<h5>Responsibility</h5>
<p>Guarantees storage consistency when an operation fails.</p>

<h5>Input</h5>
<p>None.</p>

<h5>Output</h5>
<p>None.</p>

<h5>Preconditions</h5>
<ul>
    <li>An active transaction exists.</li>
</ul>

<h5>Postconditions</h5>
<ul>
    <li>All pending modifications are discarded.</li>
    <li>The active transaction is closed.</li>
</ul>

<h5>Exceptions</h5>
<ul>
    <li>TreeStorageException when the rollback operation fails.</li>
</ul>

<h5>Implementation Notes</h5>
<p>The implementation is storage-specific.</p>

<h5>Execution Notes</h5>
<ul>
    <li>Executed whenever a write operation cannot be completed successfully.</li>
    <li>Must never modify the tree structure directly.</li>
</ul>

<h5>Used By</h5>
<ul>
    <li>appendChild()</li>
    <li>insertBefore()</li>
    <li>insertAfter()</li>
    <li>moveNode()</li>
    <li>moveSubtree()</li>
    <li>deleteNode()</li>
    <li>deleteSubtree()</li>
    <li>makeRoot()</li>
    <li>mergeTree()</li>
    <li>importTree()</li>
</ul>

<hr>
<h3>Validation Primitives</h3>
<p>Validation primitives verify the correctness of an operation before any transaction, lock or structural modification is performed.</p>
<p>Validation primitives are strictly read-only and must never modify the tree structure or the storage state.</p>
<h4>validateNodeExists()</h4>
<h5>Purpose</h5>
<p>Verifies that the specified node exists in the current storage.</p>
<h5>Responsibility</h5>
<p>Prevents subsequent operations from executing on invalid or non-existing nodes.</p>
<h5>Input</h5>
<ul>
    <li>TreeNodeInterface $node</li>
</ul>
<h5>Output</h5>
<p>None.</p>
<h5>Preconditions</h5>
<ul>
    <li>A valid TreeNodeInterface instance is provided.</li>
</ul>
<h5>Postconditions</h5>
<ul>
    <li>The node is confirmed to exist.</li>
</ul>
<h5>Exceptions</h5>
<ul>
    <li>NodeNotFoundException</li>
</ul>
<h5>Implementation Notes</h5>
<p>This primitive performs validation only.</p>
<h5>Execution Notes</h5>
<ul>
    <li>Read-only operation.</li>
    <li>No transaction is required.</li>
    <li>No locks are acquired.</li>
</ul>
<h5>Used By</h5>
<ul>
    <li>appendChild()</li>
    <li>insertBefore()</li>
    <li>insertAfter()</li>
    <li>moveNode()</li>
    <li>moveSubtree()</li>
    <li>deleteNode()</li>
    <li>deleteSubtree()</li>
    <li>makeRoot()</li>
    <li>refreshNode()</li>
</ul>
<h4>validateInsert()</h4
<p>The remaining Validation primitives follow the same specification template and are documented individually in the following sections.</p>
<hr>
<h3>Lock Primitives</h3>
<p>
Lock primitives protect the integrity of the tree structure during concurrent write operations.
Their primary responsibility is to prevent conflicting modifications while maintaining transactional consistency.
</p>
<p>
Lock primitives never modify the tree structure directly.
They only acquire and release exclusive access to one or more nodes.
</p>
<h4>lockNode()</h4>
<h5>Purpose</h5>
<p>Acquires an exclusive lock on a single tree node.</p>
<h5>Responsibility</h5>
<p>Prevents concurrent write operations on the specified node.</p>
<h5>Input</h5>
<ul>
    <li>TreeNodeInterface $node</li>
</ul>
<h5>Output</h5>
<p>None.</p>
<h5>Preconditions</h5>
<ul>
    <li>The node exists.</li>
    <li>An active transaction exists.</li>
</ul>
<h5>Postconditions</h5>
<ul>
    <li>The node is locked until the transaction completes.</li>
</ul>
<h5>Exceptions</h5>
<ul>
    <li>LockException</li>
</ul>
<h5>Implementation Notes</h5>
<p>The implementation is storage-specific.</p>
<h5>Execution Notes</h5>
<ul>
    <li>Requires an active transaction.</li>
    <li>Must not modify the tree.</li>
</ul>
<h5>Used By</h5>
<ul>
    <li>appendChild()</li>
    <li>insertBefore()</li>
    <li>insertAfter()</li>
    <li>moveNode()</li>
    <li>deleteNode()</li>
</ul>
<hr>
<h4>lockSubtree()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h3>Gap Primitives</h3>
<p>
Gap primitives are responsible for modifying the Nested Set structure.
Only Gap primitives are allowed to change the <code>lft</code> and <code>rgt</code> values.
</p>
<p>
Every structural modification performed by the engine is ultimately expressed through one or more Gap primitives.
</p>
<h4>createGap()</h4>
<h5>Purpose</h5>
<p>Creates free space inside the Nested Set structure.</p>
<h5>Responsibility</h5>
<p>Shifts existing boundaries in order to insert new nodes or subtrees.</p>
<h5>Input</h5>
<ul>
    <li>Insertion point</li>
    <li>Gap width</li>
</ul>
<h5>Output</h5>
<p>None.</p>
<h5>Preconditions</h5>
<ul>
    <li>An active transaction exists.</li>
    <li>The insertion point is valid.</li>
</ul>
<h5>Postconditions</h5>
<ul>
    <li>The requested free space exists.</li>
</ul>
<h5>Exceptions</h5>
<ul>
    <li>TreeStorageException</li>
</ul>
<h5>Implementation Notes</h5>
<p>This primitive modifies only the Nested Set boundaries.</p>
<h5>Execution Notes</h5>
<ul>
    <li>Requires an exclusive lock.</li>
    <li>Must execute inside a transaction.</li>
</ul>
<h5>Used By</h5>
<ul>
    <li>appendChild()</li>
    <li>insertBefore()</li>
    <li>insertAfter()</li>
    <li>moveSubtree()</li>
    <li>mergeTree()</li>
</ul>
<hr>
<h4>closeGap()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h4>moveGap()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h3>Node Primitives</h3>
<p>
Node primitives manipulate node records without changing the Nested Set structure directly.
</p>
<h4>insertNodeRecord()</h4>
<h5>Purpose</h5>
<p>Creates a new node record.</p>
<h5>Responsibility</h5>
<p>Persists node data using the boundaries prepared by Gap primitives.</p>
<h5>Execution Notes</h5>
<ul>
    <li>Requires an active transaction.</li>
    <li>Must never calculate lft/rgt values.</li>
</ul>
<hr>
<h4>updateNodeRecord()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h4>deleteNodeRecord()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h4>refreshNode()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h3>Query Primitives</h3>
<p>
Query primitives provide read-only access to the tree structure.
</p>
<p>
Query primitives never modify storage.
</p>
<h4>loadNode()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h4>loadChildren()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h4>loadParent()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h4>loadSubtree()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h3>Refresh Primitives</h3>
<p>
Refresh primitives synchronize storage-specific runtime objects after write operations.
</p>
<h4>refreshNode()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h4>clearCache()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h3>Utility Primitives</h3>
<p>
Utility primitives provide reusable calculations used by high-level operations.
</p>
<h4>calculateLevel()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h4>calculateWidth()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h4>isLeaf()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h4>isRoot()</h4>
<p>The specification follows the same primitive template.</p>
<hr>
<h4>isDescendant()</h4>
<p>The specification follows the same primitive template.</p>
<h2>High-Level Operations</h2>
<p>
High-level operations represent the public API of the PhyloTree Nested Set Engine.
Unlike primitive operations, they do not perform individual low-level tasks.
Instead, they orchestrate multiple primitive operations into complete workflows.
</p>
<p>
Every high-level operation must be deterministic, transactional and composed exclusively from primitive operations defined by this specification.
</p>
<h3>General Execution Flow</h3>
<ol>
    <li>Validate input parameters.</li>
    <li>Start a transaction.</li>
    <li>Acquire required locks.</li>
    <li>Execute one or more structural primitives.</li>
    <li>Synchronize runtime objects if required.</li>
    <li>Commit the transaction.</li>
    <li>Rollback the transaction if any step fails.</li>
</ol>
<h3>Examples of High-Level Operations</h3>
<ul>
    <li>appendChild()</li>
    <li>prependChild()</li>
    <li>insertBefore()</li>
    <li>insertAfter()</li>
    <li>moveNode()</li>
    <li>moveSubtree()</li>
    <li>deleteNode()</li>
    <li>deleteSubtree()</li>
    <li>replaceNode()</li>
    <li>mergeTree()</li>
    <li>cloneSubtree()</li>
    <li>makeRoot()</li>
    <li>importTree()</li>
    <li>exportTree()</li>
</ul>
<hr>
<h2>SQL Algorithms</h2>
<p>
The Nested Set Engine is based on a small set of reusable SQL algorithms.
Every structural modification performed by the engine is ultimately translated into one or more of these algorithms.
</p>
<h3>Core Algorithms</h3>
<ul>
    <li>Create Gap</li>
    <li>Close Gap</li>
    <li>Move Window</li>
    <li>Insert Node Record</li>
    <li>Delete Node Record</li>
    <li>Update Parent Information</li>
    <li>Refresh Runtime Objects</li>
</ul>
<p>
Algorithm specifications are implementation-independent.
Individual storage backends are responsible for translating these algorithms into optimized storage-specific commands.
</p>
<hr>
<h2>Complexity Analysis</h2>
<p>
The following table summarizes the expected complexity of the primary operations.
Actual performance depends on the storage backend and indexing strategy.
</p>

| Operation | Expected Complexity |
|-----------|--------------------:|
| Node Lookup | O(log n) *(indexed)* |
| Load Children | O(k) |
| Load Subtree | O(k) |
| Insert Node | O(n) |
| Delete Node | O(n) |
| Move Subtree | O(n) |
| Validation | O(1) – O(log n) |
| Lock Acquisition | Storage dependent |

<p>
Large trees rely on indexed <code>lft</code>, <code>rgt</code> and identifier columns for optimal performance.
</p>
<hr>
<h2>Future Extensions</h2>
<p>
The architecture has been intentionally designed to support future extensions without modifying the primitive operation model.
</p>
<h3>Planned Features</h3>
<ul>
    <li>Workspace support</li>
    <li>Workspace merge engine</li>
    <li>Undo / Redo</li>
    <li>Versioned trees</li>
    <li>Tree snapshots</li>
    <li>Tree diff engine</li>
    <li>Conflict detection</li>
    <li>Bulk import/export</li>
    <li>Newick import/export</li>
    <li>NEXUS import/export</li>
    <li>PhyloXML import/export</li>
    <li>JSON serialization</li>
    <li>Parallel processing</li>
    <li>Distributed storage backends</li>
</ul>
<h3>Storage Backends</h3>
<ul>
    <li>Doctrine DBAL</li>
    <li>Doctrine ORM</li>
    <li>InMemory Storage</li>
    <li>Native PostgreSQL</li>
    <li>Native MySQL / MariaDB</li>
    <li>SQLite</li>
</ul>
<h3>Design Objectives</h3>
<ul>
    <li>Maintain implementation-independent algorithms.</li>
    <li>Preserve backward compatibility of primitive operations.</li>
    <li>Keep high-level operations deterministic.</li>
    <li>Support multiple storage implementations.</li>
    <li>Minimize duplicated algorithms.</li>
    <li>Remain scalable for trees containing millions of nodes.</li>
</ul>
<hr>
<h2>End of Specification</h2>
<p>
This specification defines the architectural foundation of the PhyloTree Nested Set Engine.
Every storage backend, primitive operation and high-level workflow must conform to the rules defined in this document.
Future revisions should extend this specification while preserving the existing architectural principles.
</p>
