function definitions(process) {
  const { id, key, name, category, versionTag, tenantId, resource } = process

  return {
    ...process,
    id,
    uuid: id,
    key,
    name,
    type: getDiagramTypeByCategory(category),
    version: versionTag,
    tenantId,
    resource,
  }
}

function mergeDefenitions(processes) {
  const result = new Array()

  const mergedProcesses = processes.reduce((acc, data) => {
    const { id, definition, instances, failedJobs, incidents } = data
    const { key, name, deploymentId } = definition

    acc[key] ??= {
      key,
      '@class': data['@class'],
      name,
      versions: [],
      definitions: [],
      instances: 0,
      failedJobs: 0,
      incidents: 0,
      diagram: null,
    }

    acc[key].definitions.push(definition)
    acc[key].versions.push({
      id,
      version: definition.versionTag,
      deploymentId,
      resource: definition.resource,
    })

    acc[key].instances += instances
    acc[key].failedJobs += failedJobs
    acc[key].incidents += incidents.length

    acc[key].diagram = {
      id,
      name: definition.name,
      url: `process-definition/${id}/xml`,
      type: getDiagramTypeByCategory(definition.category),
      runningInstances: acc[key].instances,
      incidents: acc[key].incidents,
      failedJobs: acc[key].failedJobs,
    }

    return acc
  }, [])

  for (let item in mergedProcesses) {
    result.push(mergedProcesses[item])
  }

  return result
}

function getDiagramTypeByCategory(category) {
  switch (category) {
    case 'http://www.omg.org/spec/BPMN/20100524/MODEL':
      return 'bpmn'
    default:
      return category.substring(category.lastIndexOf('/') + 1)
  }
}

export { definitions, mergeDefenitions, getDiagramTypeByCategory }
