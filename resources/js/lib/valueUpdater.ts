import type { Ref } from 'vue'

/**
 * A utility function to update a Vue Ref based on a new value or an updater function.
 * This is common when working with @tanstack/vue-table's on[State]Change callbacks.
 *
 * @param updaterOrValue The new value or an updater function (oldValue => newValue).
 * @param ref The Vue Ref to be updated.
 */
export function valueUpdater<T>(updaterOrValue: T | ((prev: T) => T), ref: Ref<T>) {
  // Check if the updaterOrValue is a function
  if (typeof updaterOrValue === 'function') {
    // If it's a function, call it with the current ref value
    // and update the ref with the returned new value
    ref.value = (updaterOrValue as (prev: T) => T)(ref.value);
  } else {
    // If it's not a function, it's a direct new value
    // so assign it directly to the ref
    ref.value = updaterOrValue;
  }
}